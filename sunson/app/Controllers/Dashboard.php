<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Dashboard extends BaseController
{
    protected $helpers = ['form'];

    private const DEPARTMENTS = [
        'Administration', 'IT', 'Dispatch', 'Accounting',
        'HR', 'Marketing', 'Sales', 'Customer Service',
    ];

    private const EMPLOYEE_ROLES = ['admin', 'hr', 'dispatcher', 'technician'];
    public function index()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }

        $user = $this->currentUser();
        $db = db_connect();
        $data = [
            'user' => $user,
            'bookings' => [],
            'notifications' => $db->table('notifications')
                ->groupStart()
                    ->where('user_id', $user['id'])
                    ->orWhere('user_id', null)
                ->groupEnd()
                ->orderBy('created_at', 'DESC')->limit(8)->get()->getResultArray(),
            'technicians' => [],
            'employees' => [],
            'problems' => [],
            'tickets' => [],
            'attendance' => null,
            'services' => $db->table('service_settings')->where('is_available', 1)->orderBy('service_type')->get()->getResultArray(),
            'serviceSettings' => [],
            'quotes' => [],
            'auditLogs' => [],
            'preferences' => ['service_notifications' => 1, 'promotional_notifications' => 0],
            'serviceUsage' => [],
            'departments' => self::DEPARTMENTS,
            'employeeRoles' => session('role') === 'hr'
                ? ['hr', 'dispatcher', 'technician']
                : self::EMPLOYEE_ROLES,
        ];

        if ($user['role'] === 'customer') {
            $preferences = $db->table('user_preferences')->where('user_id', $user['id'])->get()->getRowArray();
            if ($preferences) {
                $data['preferences'] = $preferences;
            }
            $data['bookings'] = $db->table('service_bookings')
                ->select('service_bookings.*, payments.amount AS paid_amount, payments.receipt_number, payments.payment_method, payments.paid_at')
                ->join('payments', 'payments.booking_id = service_bookings.id', 'left')
                ->where('customer_id', $user['id'])->orderBy('scheduled_at', 'DESC')
                ->get()->getResultArray();
            $data['tickets'] = $db->table('support_tickets')
                ->where('customer_id', $user['id'])->orderBy('created_at', 'DESC')
                ->get()->getResultArray();
        } elseif ($user['role'] === 'technician') {
            $data['bookings'] = $db->table('service_bookings')
                ->select('service_bookings.id, service_bookings.service_type, service_bookings.scheduled_at, service_bookings.address, service_bookings.status, users.first_name, users.last_name')
                ->join('users', 'users.id = service_bookings.customer_id')
                ->where('service_bookings.assigned_technician_id', $user['id'])
                ->whereIn('service_bookings.status', ['approved', 'en_route', 'in_progress'])
                ->orderBy('service_bookings.scheduled_at', 'ASC')->get()->getResultArray();
            $data['attendance'] = $db->table('attendance')
                ->where('employee_id', $user['id'])->where('checked_out_at', null)
                ->orderBy('checked_in_at', 'DESC')->get()->getRowArray();
        } elseif (in_array($user['role'], ['admin', 'dispatcher'], true)) {
            $data['bookings'] = $db->table('service_bookings')
                ->select('service_bookings.*, customers.first_name AS customer_first_name, customers.last_name AS customer_last_name, tech.first_name AS technician_first_name, tech.last_name AS technician_last_name, payments.receipt_number, payments.amount AS paid_amount')
                ->join('users AS customers', 'customers.id = service_bookings.customer_id')
                ->join('users AS tech', 'tech.id = service_bookings.assigned_technician_id', 'left')
                ->join('payments', 'payments.booking_id = service_bookings.id', 'left')
                ->orderBy('service_bookings.scheduled_at', 'ASC')->get()->getResultArray();
            $data['technicians'] = (new UserModel())->select('id, first_name, last_name')
                ->where('role', 'technician')->where('is_active', 1)->findAll();
        }

        if (in_array($user['role'], ['admin', 'hr'], true)) {
            $employees = new UserModel();
            $employees->select('id, first_name, middle_name, last_name, birthdate, gender, email, phone_number, address, department, role, is_active')
                ->whereIn('role', self::EMPLOYEE_ROLES);
            if ($user['role'] === 'hr') {
                $employees->where('role !=', 'admin');
            }
            $data['employees'] = $employees->orderBy('last_name')->findAll();
        }

        if ($user['role'] === 'admin') {
            $data['problems'] = $db->table('problem_reports')
                ->select('problem_reports.*, users.first_name, users.last_name')
                ->join('users', 'users.id = problem_reports.technician_id')
                ->orderBy('problem_reports.created_at', 'DESC')->get()->getResultArray();
            $data['metrics'] = [
                'customers' => $db->table('users')->where('role', 'customer')->countAllResults(),
                'bookings' => $db->table('service_bookings')->countAllResults(),
                'revenue' => $db->table('payments')->selectSum('amount', 'total')->get()->getRow()->total ?? 0,
            ];
            $rangeStart = new \DateTimeImmutable('first day of this month');
            $rangeStart = $rangeStart->modify('-5 months');
            $rangeEnd = $rangeStart->modify('+6 months');
            $revenueRows = $db->table('payments')
                ->select('YEAR(paid_at) AS paid_year, MONTH(paid_at) AS paid_month, SUM(amount) AS total', false)
                ->where('paid_at >=', $rangeStart->format('Y-m-d H:i:s'))
                ->where('paid_at <', $rangeEnd->format('Y-m-d H:i:s'))
                ->groupBy('paid_year')
                ->groupBy('paid_month')
                ->get()->getResultArray();
            $revenueByMonth = [];
            foreach ($revenueRows as $revenueRow) {
                $monthKey = sprintf('%04d-%02d', (int) $revenueRow['paid_year'], (int) $revenueRow['paid_month']);
                $revenueByMonth[$monthKey] = (float) $revenueRow['total'];
            }
            $data['monthlyRevenue'] = ['labels' => [], 'values' => []];
            for ($monthOffset = 0; $monthOffset < 6; $monthOffset++) {
                $month = $rangeStart->modify("+{$monthOffset} months");
                $data['monthlyRevenue']['labels'][] = $month->format('M Y');
                $data['monthlyRevenue']['values'][] = round($revenueByMonth[$month->format('Y-m')] ?? 0, 2);
            }
            $data['serviceUsage'] = $db->table('service_bookings')
                ->select('service_type, COUNT(*) AS total')->groupBy('service_type')
                ->orderBy('total', 'DESC')->get()->getResultArray();
            $data['serviceSettings'] = $db->table('service_settings')->orderBy('service_type')->get()->getResultArray();
            $data['quotes'] = $db->table('quote_requests')->orderBy('created_at', 'DESC')->limit(30)->get()->getResultArray();
            $data['auditLogs'] = $db->table('audit_logs')
                ->select('audit_logs.action, audit_logs.details, audit_logs.created_at, users.first_name, users.last_name')
                ->join('users', 'users.id = audit_logs.actor_id')
                ->orderBy('audit_logs.created_at', 'DESC')->limit(30)->get()->getResultArray();
            $data['tickets'] = $db->table('support_tickets')
                ->select('support_tickets.*, users.first_name, users.last_name')
                ->join('users', 'users.id = support_tickets.customer_id')
                ->orderBy('support_tickets.created_at', 'DESC')->limit(30)->get()->getResultArray();
        }

        return view('dashboard/index', $data);
    }

    public function bookService()
    {
        if ($redirect = $this->guard(['customer'])) {
            return $redirect;
        }

        $serviceSettings = db_connect()->table('service_settings')->where('is_available', 1)->get()->getResultArray();
        if ($serviceSettings === []) {
            return redirect()->to('/dashboard')->with('error', 'There are no services available to book right now.');
        }
        $rules = [
            'service_type' => 'required|max_length[50]',
            'scheduled_at' => 'required|valid_date[Y-m-d\TH:i]',
            'address' => 'required|max_length[1000]',
            'notes' => 'permit_empty|max_length[2000]',
        ];
        if (! $this->validate($rules)) {
            return $this->backWithErrors();
        }

        $scheduledAt = strtotime((string) $this->request->getPost('scheduled_at'));
        if ($scheduledAt === false || $scheduledAt <= time()) {
            return redirect()->to('/dashboard')->with('error', 'Choose a service time in the future.');
        }

        $setting = null;
        foreach ($serviceSettings as $service) {
            if ($service['service_type'] === $this->request->getPost('service_type')) {
                $setting = $service;
                break;
            }
        }
        if (! $setting) {
            return redirect()->to('/dashboard')->with('error', 'This service is no longer available to book.');
        }
        $created = db_connect()->table('service_bookings')->insert([
            'customer_id' => session('user_id'),
            'service_type' => $this->request->getPost('service_type'),
            'scheduled_at' => date('Y-m-d H:i:s', $scheduledAt),
            'address' => $this->request->getPost('address'),
            'notes' => $this->request->getPost('notes'),
            'price' => $setting['price'] ?? null,
        ]);
        if (! $created) {
            return redirect()->to('/dashboard')->with('error', 'We could not save your service request. Please try again.');
        }

        return redirect()->to('/dashboard')->with('success', 'Your service request has been submitted for review.');
    }

    public function cancelBooking(int $id)
    {
        if ($redirect = $this->guard(['customer'])) {
            return $redirect;
        }

        $db = db_connect();
        $booking = $db->table('service_bookings')->where('id', $id)
            ->where('customer_id', session('user_id'))->whereIn('status', ['pending', 'approved'])
            ->get()->getRowArray();
        if (! $booking) {
            return redirect()->to('/dashboard')->with('error', 'This booking cannot be cancelled.');
        }

        $db->table('service_bookings')->where('id', $id)->update(['status' => 'cancelled']);

        return redirect()->to('/dashboard')->with('success', 'Booking cancelled.');
    }

    public function rescheduleBooking(int $id)
    {
        if ($redirect = $this->guard(['customer'])) {
            return $redirect;
        }

        if (! $this->validate(['scheduled_at' => 'required|valid_date[Y-m-d\TH:i]'])) {
            return $this->backWithErrors();
        }
        $scheduledAt = strtotime((string) $this->request->getPost('scheduled_at'));
        $db = db_connect();
        $booking = $db->table('service_bookings')->where('id', $id)
            ->where('customer_id', session('user_id'))->whereIn('status', ['pending', 'approved'])
            ->get()->getRowArray();
        if (! $booking || $scheduledAt === false || $scheduledAt <= time()) {
            return redirect()->to('/dashboard')->with('error', 'Choose a future time for an active booking.');
        }

        $db->table('service_bookings')->where('id', $id)->update([
            'scheduled_at' => date('Y-m-d H:i:s', $scheduledAt),
            'status' => 'pending',
        ]);

        return redirect()->to('/dashboard')->with('success', 'Reschedule request sent for review.');
    }

    public function adjustSchedule(int $id)
    {
        if ($redirect = $this->guard(['admin', 'dispatcher'])) {
            return $redirect;
        }
        if (! $this->validate(['scheduled_at' => 'required|valid_date[Y-m-d\TH:i]'])) {
            return $this->backWithErrors();
        }

        $scheduledAt = strtotime((string) $this->request->getPost('scheduled_at'));
        $db = db_connect();
        $booking = $db->table('service_bookings')->where('id', $id)
            ->whereIn('status', ['pending', 'approved', 'en_route', 'in_progress'])->get()->getRowArray();
        if (! $booking || $scheduledAt === false || $scheduledAt <= time()) {
            return redirect()->to('/dashboard')->with('error', 'Only active bookings can be moved to a future time.');
        }

        $db->table('service_bookings')->where('id', $id)->update([
            'scheduled_at' => date('Y-m-d H:i:s', $scheduledAt),
        ]);
        $this->notify($booking['customer_id'], 'Service schedule updated', 'Your ' . $booking['service_type'] . ' booking is now scheduled for ' . date('M j, Y g:i A', $scheduledAt) . '.');
        $this->audit('booking_rescheduled', 'Booking #' . $id);

        return redirect()->to('/dashboard')->with('success', 'Booking schedule updated.');
    }

    public function reviewBooking(int $id)
    {
        if ($redirect = $this->guard(['admin', 'dispatcher'])) {
            return $redirect;
        }

        $status = (string) $this->request->getPost('status');
        if (! in_array($status, ['approved', 'declined'], true)) {
            return redirect()->to('/dashboard')->with('error', 'Select a valid booking decision.');
        }

        $db = db_connect();
        $booking = $db->table('service_bookings')->where('id', $id)->where('status', 'pending')->get()->getRowArray();
        if (! $booking) {
            return redirect()->to('/dashboard')->with('error', 'This request is no longer pending.');
        }

        if (! $db->table('service_bookings')->where('id', $id)->update(['status' => $status])) {
            return redirect()->to('/dashboard')->with('error', 'The booking decision could not be saved.');
        }
        $this->audit('booking_' . $status, 'Booking #' . $id);
        $this->notify($booking['customer_id'], 'Service request ' . $status, 'Your ' . $booking['service_type'] . ' request was ' . $status . '.');

        return redirect()->to('/dashboard')->with('success', 'Booking updated.');
    }

    public function assignTechnician(int $id)
    {
        if ($redirect = $this->guard(['admin', 'dispatcher'])) {
            return $redirect;
        }

        $technicianId = (int) $this->request->getPost('technician_id');
        $technician = (new UserModel())->where('id', $technicianId)->where('role', 'technician')
            ->where('is_active', 1)->first();
        if (! $technician) {
            return redirect()->to('/dashboard')->with('error', 'Choose an active technician.');
        }

        $db = db_connect();
        $booking = $db->table('service_bookings')->where('id', $id)
            ->whereIn('status', ['approved', 'en_route', 'in_progress'])->get()->getRowArray();
        if (! $booking) {
            return redirect()->to('/dashboard')->with('error', 'Only approved active bookings can be assigned.');
        }

        if (! $db->table('service_bookings')->where('id', $id)->update(['assigned_technician_id' => $technicianId])) {
            return redirect()->to('/dashboard')->with('error', 'Technician assignment could not be saved.');
        }
        $this->audit('technician_assigned', 'Booking #' . $id . ' assigned to employee #' . $technicianId);
        $this->notify($technicianId, 'New assigned service', $booking['service_type'] . ' scheduled for ' . $booking['scheduled_at'] . '.');

        return redirect()->to('/dashboard')->with('success', 'Technician assigned.');
    }

    public function updateJobStatus(int $id)
    {
        if ($redirect = $this->guard(['technician'])) {
            return $redirect;
        }

        $status = (string) $this->request->getPost('status');
        if (! in_array($status, ['en_route', 'in_progress', 'completed'], true)) {
            return redirect()->to('/dashboard')->with('error', 'Select a valid job status.');
        }

        $db = db_connect();
        $job = $db->table('service_bookings')->where('id', $id)
            ->where('assigned_technician_id', session('user_id'))->get()->getRowArray();
        if (! $job) {
            return redirect()->to('/dashboard')->with('error', 'That job is not assigned to you.');
        }
        $updated = $db->table('service_bookings')
            ->where('id', $id)->where('assigned_technician_id', session('user_id'))
            ->whereIn('status', ['approved', 'en_route', 'in_progress'])
            ->update(['status' => $status]);
        if (! $updated) {
            return redirect()->to('/dashboard')->with('error', 'That job could not be updated.');
        }
        if ($status === 'completed') {
            $this->notify($job['customer_id'], 'Service completed', 'Your ' . $job['service_type'] . ' service has been marked complete.');
        }

        return redirect()->to('/dashboard')->with('success', 'Job status updated.');
    }

    public function recordPayment(int $id)
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }
        if (! $this->validate([
            'amount' => 'required|decimal|greater_than[0]',
            'payment_method' => 'required|in_list[Cash,Bank Transfer,Card]',
        ])) {
            return $this->backWithErrors();
        }

        $db = db_connect();
        $booking = $db->table('service_bookings')->where('id', $id)
            ->where('status', 'completed')->get()->getRowArray();
        if (! $booking) {
            return redirect()->to('/dashboard')->with('error', 'Payments can be recorded only for completed services.');
        }
        if ($db->table('payments')->where('booking_id', $id)->countAllResults() > 0) {
            return redirect()->to('/dashboard')->with('error', 'A receipt already exists for this service.');
        }

        $receiptNumber = 'SS-' . date('Ymd') . '-' . $id . '-' . strtoupper(bin2hex(random_bytes(4)));
        $saved = $db->table('payments')->insert([
            'booking_id' => $id,
            'recorded_by' => session('user_id'),
            'amount' => $this->request->getPost('amount'),
            'payment_method' => $this->request->getPost('payment_method'),
            'receipt_number' => $receiptNumber,
            'paid_at' => date('Y-m-d H:i:s'),
        ]);
        if (! $saved) {
            return redirect()->to('/dashboard')->with('error', 'Payment and receipt could not be saved.');
        }
        $this->audit('payment_recorded', 'Receipt ' . $receiptNumber . ' for booking #' . $id);
        $this->notify((int) $booking['customer_id'], 'Payment receipt available', 'Receipt ' . $receiptNumber . ' is available in your service history.');

        return redirect()->to('/dashboard')->with('success', 'Payment recorded and receipt created.');
    }

    public function createEmployee()
    {
        if ($redirect = $this->guard(['admin', 'hr'])) {
            return $redirect;
        }

        $employeeRoles = self::EMPLOYEE_ROLES;
        if (session('role') === 'hr') {
            $employeeRoles = array_values(array_diff($employeeRoles, ['admin']));
        }
        $rules = [
            'first_name' => 'required|max_length[50]',
            'middle_name' => 'permit_empty|max_length[50]',
            'last_name' => 'required|max_length[50]',
            'birthdate' => 'required|valid_date[Y-m-d]',
            'gender' => 'required|max_length[20]',
            'email' => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'phone_number' => 'required|regex_match[/^[0-9+\-\s]{7,20}$/]',
            'address' => 'required|max_length[1000]',
            'username' => 'required|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[12]',
            'department' => 'required|in_list[' . implode(',', self::DEPARTMENTS) . ']',
            'role' => 'required|in_list[' . implode(',', $employeeRoles) . ']',
        ];
        if (! $this->validate($rules)) {
            return $this->backWithErrors();
        }

        $employeeId = (new UserModel())->insert([
            'first_name' => $this->request->getPost('first_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'last_name' => $this->request->getPost('last_name'),
            'birthdate' => $this->request->getPost('birthdate'),
            'gender' => $this->request->getPost('gender'),
            'email' => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number'),
            'address' => $this->request->getPost('address'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'department' => $this->request->getPost('department'),
            'role' => $this->request->getPost('role'),
            'is_active' => 1,
            'must_change_password' => 1,
        ]);
        if (! $employeeId) {
            return redirect()->to('/dashboard')->with('error', 'Employee account could not be created.');
        }
        $this->audit('employee_created', 'Created account for ' . $this->request->getPost('username'));

        return redirect()->to('/dashboard')->with('success', 'Employee account created. The employee must change their temporary password at first login.');
    }

    public function deactivateEmployee(int $id)
    {
        if ($redirect = $this->guard(['admin', 'hr'])) {
            return $redirect;
        }
        if ($id === (int) session('user_id')) {
            return redirect()->to('/dashboard')->with('error', 'You cannot deactivate your own account.');
        }

        $employee = (new UserModel())->where('id', $id)->whereIn('role', self::EMPLOYEE_ROLES)->first();
        if (! $employee || (session('role') === 'hr' && $employee['role'] === 'admin')) {
            return redirect()->to('/dashboard')->with('error', 'You do not have permission to deactivate this employee.');
        }
        $updated = (new UserModel())->where('id', $id)->whereIn('role', self::EMPLOYEE_ROLES)
            ->set('is_active', 0)->update();
        if (! $updated) {
            return redirect()->to('/dashboard')->with('error', 'Employee account was not found.');
        }
        $this->audit('employee_deactivated', 'Deactivated employee #' . $id);

        return redirect()->to('/dashboard')->with('success', 'Employee account deactivated.');
    }

    public function updateEmployee(int $id)
    {
        if ($redirect = $this->guard(['admin', 'hr'])) {
            return $redirect;
        }
        $employeeRoles = self::EMPLOYEE_ROLES;
        if (session('role') === 'hr') {
            $employeeRoles = array_values(array_diff($employeeRoles, ['admin']));
        }
        if (! $this->validate([
            'first_name' => 'required|max_length[50]',
            'middle_name' => 'permit_empty|max_length[50]',
            'last_name' => 'required|max_length[50]',
            'birthdate' => 'required|valid_date[Y-m-d]',
            'gender' => 'required|max_length[20]',
            'email' => 'required|valid_email|max_length[100]|is_unique[users.email,id,' . $id . ']',
            'phone_number' => 'required|regex_match[/^[0-9+\-\s]{7,20}$/]',
            'address' => 'required|max_length[1000]',
            'department' => 'required|in_list[' . implode(',', self::DEPARTMENTS) . ']',
            'role' => 'required|in_list[' . implode(',', $employeeRoles) . ']',
        ])) {
            return $this->backWithErrors();
        }

        $model = new UserModel();
        $employee = $model->where('id', $id)->whereIn('role', self::EMPLOYEE_ROLES)->first();
        if (! $employee || (session('role') === 'hr' && $employee['role'] === 'admin')) {
            return redirect()->to('/dashboard')->with('error', 'You do not have permission to edit this employee.');
        }
        $updated = $model->where('id', $id)->whereIn('role', self::EMPLOYEE_ROLES)
            ->set([
                'first_name' => $this->request->getPost('first_name'),
                'middle_name' => $this->request->getPost('middle_name'),
                'last_name' => $this->request->getPost('last_name'),
                'birthdate' => $this->request->getPost('birthdate'),
                'gender' => $this->request->getPost('gender'),
                'email' => $this->request->getPost('email'),
                'phone_number' => $this->request->getPost('phone_number'),
                'address' => $this->request->getPost('address'),
                'department' => $this->request->getPost('department'),
                'role' => $this->request->getPost('role'),
            ])->update();
        if (! $updated) {
            return redirect()->to('/dashboard')->with('error', 'Employee account was not found or not updated.');
        }
        $this->audit('employee_updated', 'Updated employee #' . $id);

        return redirect()->to('/dashboard')->with('success', 'Employee account updated.');
    }

    public function checkIn()
    {
        if ($redirect = $this->guard(self::EMPLOYEE_ROLES)) {
            return $redirect;
        }

        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');
        if (! is_numeric($latitude) || ! is_numeric($longitude)
            || (float) $latitude < -90 || (float) $latitude > 90
            || (float) $longitude < -180 || (float) $longitude > 180) {
            return redirect()->to('/dashboard')->with('error', 'Allow browser location to record your check-in.');
        }

        $db = db_connect();
        $open = $db->table('attendance')->where('employee_id', session('user_id'))
            ->where('checked_out_at', null)->countAllResults();
        if ($open > 0) {
            return redirect()->to('/dashboard')->with('error', 'Check out of your current attendance before checking in again.');
        }

        $employee = $this->currentUser();
        if (! $employee) {
            return redirect()->to('/login')->with('error', 'Your account is unavailable.');
        }
        if (! $db->table('attendance')->insert([
            'employee_id' => session('user_id'),
            'employee_name' => $employee['first_name'] . ' ' . $employee['last_name'],
            'checked_in_at' => date('Y-m-d H:i:s'),
            'check_in_latitude' => $latitude,
            'check_in_longitude' => $longitude,
        ])) {
            return redirect()->to('/dashboard')->with('error', 'Attendance check-in could not be recorded.');
        }

        return redirect()->to('/dashboard')->with('success', 'Attendance check-in recorded.');
    }

    public function checkOut()
    {
        if ($redirect = $this->guard(self::EMPLOYEE_ROLES)) {
            return $redirect;
        }

        $latitude = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');
        if (! is_numeric($latitude) || ! is_numeric($longitude)
            || (float) $latitude < -90 || (float) $latitude > 90
            || (float) $longitude < -180 || (float) $longitude > 180) {
            return redirect()->to('/dashboard')->with('error', 'Allow browser location to record your check-out.');
        }

        $db = db_connect();
        $attendance = $db->table('attendance')->where('employee_id', session('user_id'))
            ->where('checked_out_at', null)->orderBy('checked_in_at', 'DESC')->get()->getRowArray();
        if (! $attendance) {
            return redirect()->to('/dashboard')->with('error', 'No active attendance record to check out.');
        }

        if (! $db->table('attendance')->where('id', $attendance['id'])->update([
            'checked_out_at' => date('Y-m-d H:i:s'),
            'check_out_latitude' => $latitude,
            'check_out_longitude' => $longitude,
        ])) {
            return redirect()->to('/dashboard')->with('error', 'Attendance check-out could not be recorded.');
        }

        return redirect()->to('/dashboard')->with('success', 'Attendance check-out recorded.');
    }

    public function reportProblem()
    {
        if ($redirect = $this->guard(['technician'])) {
            return $redirect;
        }

        if (! $this->validate([
            'category' => 'required|max_length[80]',
            'description' => 'required|max_length[4000]',
        ])) {
            return $this->backWithErrors();
        }

        $bookingId = (int) $this->request->getPost('booking_id');
        if ($bookingId > 0) {
            $assigned = db_connect()->table('service_bookings')->where('id', $bookingId)
                ->where('assigned_technician_id', session('user_id'))->countAllResults();
            if (! $assigned) {
                return redirect()->to('/dashboard')->with('error', 'You can report issues only for jobs assigned to you.');
            }
        }

        if (! db_connect()->table('problem_reports')->insert([
            'technician_id' => session('user_id'),
            'booking_id' => $bookingId ?: null,
            'category' => $this->request->getPost('category'),
            'description' => $this->request->getPost('description'),
        ])) {
            return redirect()->to('/dashboard')->with('error', 'Problem report could not be saved.');
        }

        return redirect()->to('/dashboard')->with('success', 'Problem report sent to the admin team.');
    }

    public function sendAnnouncement()
    {
        if ($redirect = $this->guard(['admin', 'hr'])) {
            return $redirect;
        }
        if (! $this->validate([
            'title' => 'required|max_length[150]',
            'message' => 'required|max_length[4000]',
            'audience' => 'required|in_list[employees,customers]',
            'type' => 'required|in_list[announcement,reminder,promotion,system_update]',
        ])) {
            return $this->backWithErrors();
        }

        $audience = (string) $this->request->getPost('audience');
        $type = (string) $this->request->getPost('type');
        if (session('role') === 'hr' && $audience !== 'employees') {
            return redirect()->to('/dashboard')->with('error', 'HR announcements can be sent to employees only.');
        }

        $recipientsQuery = db_connect()->table('users')->select('users.id')->where('users.is_active', 1);
        if ($audience === 'customers') {
            $recipientsQuery->where('users.role', 'customer');
            if ($type === 'promotion') {
                $recipientsQuery->join('user_preferences', 'user_preferences.user_id = users.id')
                    ->where('user_preferences.promotional_notifications', 1);
            }
        } else {
            $recipientsQuery->whereIn('users.role', self::EMPLOYEE_ROLES);
        }
        $recipients = $recipientsQuery->get()->getResultArray();
        $rows = [];
        foreach ($recipients as $recipient) {
            $rows[] = [
                'user_id' => $recipient['id'],
                'sender_id' => session('user_id'),
                'title' => $this->request->getPost('title'),
                'message' => $this->request->getPost('message'),
                'type' => $type,
            ];
        }
        if ($rows !== []) {
            if (db_connect()->table('notifications')->insertBatch($rows) === false) {
                return redirect()->to('/dashboard')->with('error', 'Notification could not be sent.');
            }
        }
        $this->audit('notification_sent', $type . ' to ' . $audience . ': ' . $this->request->getPost('title'));

        return redirect()->to('/dashboard')->with('success', 'Notification sent.');
    }

    public function updateService(int $id)
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }
        if (! $this->validate([
            'service_type' => 'required|max_length[50]|is_unique[service_settings.service_type,id,' . $id . ']',
            'price' => 'permit_empty|decimal|greater_than_equal_to[0]',
            'is_available' => 'required|in_list[0,1]',
        ])) {
            return $this->backWithErrors();
        }

        $updated = db_connect()->table('service_settings')->where('id', $id)->update([
            'service_type' => $this->request->getPost('service_type'),
            'price' => $this->request->getPost('price') === '' ? null : $this->request->getPost('price'),
            'is_available' => $this->request->getPost('is_available') === '1' ? 1 : 0,
        ]);
        if (! $updated) {
            return redirect()->to('/dashboard')->with('error', 'Service settings could not be updated.');
        }
        $this->audit('service_settings_updated', 'Updated service setting #' . $id);

        return redirect()->to('/dashboard')->with('success', 'Service settings updated.');
    }

    public function createService()
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }
        if (! $this->validate([
            'service_type' => 'required|max_length[50]|is_unique[service_settings.service_type]',
            'price' => 'permit_empty|decimal|greater_than_equal_to[0]',
            'is_available' => 'required|in_list[0,1]',
        ])) {
            return $this->backWithErrors();
        }

        if (! db_connect()->table('service_settings')->insert([
            'service_type' => trim((string) $this->request->getPost('service_type')),
            'price' => $this->request->getPost('price') === '' ? null : $this->request->getPost('price'),
            'is_available' => $this->request->getPost('is_available') === '1' ? 1 : 0,
        ])) {
            return redirect()->to('/dashboard')->with('error', 'Service category could not be saved.');
        }
        $this->audit('service_category_created', 'Created service category ' . trim((string) $this->request->getPost('service_type')));

        return redirect()->to('/dashboard')->with('success', 'Service category added.');
    }

    public function updateQuote(int $id)
    {
        if ($redirect = $this->guard(['admin'])) {
            return $redirect;
        }
        $status = (string) $this->request->getPost('status');
        if (! in_array($status, ['new', 'reviewed', 'closed'], true)) {
            return redirect()->to('/dashboard')->with('error', 'Select a valid quote status.');
        }

        $updated = db_connect()->table('quote_requests')->where('id', $id)->update(['status' => $status]);
        if (! $updated) {
            return redirect()->to('/dashboard')->with('error', 'Quote request could not be updated.');
        }
        $this->audit('quote_status_updated', 'Quote #' . $id . ' marked ' . $status);

        return redirect()->to('/dashboard')->with('success', 'Quote request updated.');
    }

    public function createTicket()
    {
        if ($redirect = $this->guard(['customer'])) {
            return $redirect;
        }
        if (! $this->validate([
            'subject' => 'required|max_length[150]',
            'message' => 'required|max_length[4000]',
        ])) {
            return $this->backWithErrors();
        }

        if (! db_connect()->table('support_tickets')->insert([
            'customer_id' => session('user_id'),
            'subject' => $this->request->getPost('subject'),
            'message' => $this->request->getPost('message'),
        ])) {
            return redirect()->to('/dashboard')->with('error', 'Support request could not be saved.');
        }

        return redirect()->to('/dashboard')->with('success', 'Support request submitted.');
    }

    public function updateProfile()
    {
        if ($redirect = $this->guard()) {
            return $redirect;
        }
        $id = (int) session('user_id');
        $rules = [
            'first_name' => 'required|max_length[50]',
            'middle_name' => 'permit_empty|max_length[50]',
            'last_name' => 'required|max_length[50]',
            'birthdate' => 'required|valid_date[Y-m-d]',
            'gender' => 'required|max_length[20]',
            'email' => 'required|valid_email|max_length[100]|is_unique[users.email,id,' . $id . ']',
            'phone_number' => 'required|regex_match[/^[0-9+\-\s]{7,20}$/]',
            'address' => 'required|max_length[1000]',
        ];
        if (! $this->validate($rules)) {
            return $this->backWithErrors();
        }

        if (! (new UserModel())->update($id, [
            'first_name' => $this->request->getPost('first_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'last_name' => $this->request->getPost('last_name'),
            'birthdate' => $this->request->getPost('birthdate'),
            'gender' => $this->request->getPost('gender'),
            'email' => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number'),
            'address' => $this->request->getPost('address'),
        ])) {
            return redirect()->to('/dashboard')->with('error', 'Profile changes could not be saved.');
        }
        session()->set('first_name', $this->request->getPost('first_name'));

        return redirect()->to('/dashboard')->with('success', 'Profile updated.');
    }

    public function updatePreferences()
    {
        if ($redirect = $this->guard(['customer'])) {
            return $redirect;
        }

        $preferences = [
            'user_id' => session('user_id'),
            'service_notifications' => $this->request->getPost('service_notifications') === '1' ? 1 : 0,
            'promotional_notifications' => $this->request->getPost('promotional_notifications') === '1' ? 1 : 0,
        ];
        $db = db_connect();
        $builder = $db->table('user_preferences');
        $exists = $builder->where('user_id', session('user_id'))->countAllResults();
        if ($exists) {
            $saved = $builder->where('user_id', session('user_id'))->update($preferences);
        } else {
            $saved = $builder->insert($preferences);
        }
        if (! $saved) {
            return redirect()->to('/dashboard')->with('error', 'Notification preferences could not be saved.');
        }

        return redirect()->to('/dashboard')->with('success', 'Notification preferences saved.');
    }

    private function guard(array $roles = []): ?RedirectResponse
    {
        if (! session()->has('user_id')) {
            return redirect()->to('/login');
        }

        $lastActivity = (int) session('last_activity');
        if ($lastActivity === 0 || time() - $lastActivity > 1800) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Your session expired. Please sign in again.');
        }

        $user = $this->currentUser();
        if (! $user || ! $user['is_active']) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Your account is unavailable.');
        }
        session()->set('last_activity', time());
        session()->set([
            'first_name' => $user['first_name'],
            'role' => $user['role'],
            'must_change_password' => (bool) $user['must_change_password'],
        ]);

        if ($user['must_change_password']) {
            return redirect()->to('/change-password');
        }
        if ($roles !== [] && ! in_array($user['role'], $roles, true)) {
            return redirect()->to('/dashboard')->with('error', 'You do not have permission to perform that action.');
        }

        return null;
    }

    private function currentUser(): ?array
    {
        return (new UserModel())->find((int) session('user_id'));
    }

    private function backWithErrors()
    {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    private function notify(int $userId, string $title, string $message): void
    {
        $preferences = db_connect()->table('user_preferences')->where('user_id', $userId)->get()->getRowArray();
        if ($preferences && ! $preferences['service_notifications']) {
            return;
        }

        if (! db_connect()->table('notifications')->insert([
            'user_id' => $userId,
            'sender_id' => session('user_id'),
            'title' => $title,
            'message' => $message,
            'type' => 'schedule',
        ])) {
            log_message('error', 'Could not create a portal notification for user #{userId}.', ['userId' => $userId]);
        }
    }

    private function audit(string $action, string $details): void
    {
        if (! db_connect()->table('audit_logs')->insert([
            'actor_id' => session('user_id'),
            'action' => $action,
            'details' => $details,
        ])) {
            log_message('error', 'Could not write audit log for action {action}.', ['action' => $action]);
        }
    }
}
