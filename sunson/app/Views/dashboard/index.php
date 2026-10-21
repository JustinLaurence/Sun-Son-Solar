<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | Sun Son Solar</title>
  <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
</head>
<body>
  <header class="portal-header">
    <a class="portal-brand" href="<?= site_url('/') ?>">Sun Son <strong>Solar</strong></a>
    <div class="portal-user">
      <span><?= esc($user['first_name'] . ' ' . $user['last_name']) ?> · <?= esc(ucfirst($user['role'])) ?></span>
      <form action="<?= site_url('logout') ?>" method="post"><?= csrf_field() ?><button class="button button-quiet" type="submit">Log out</button></form>
    </div>
  </header>

  <main class="portal-main">
    <div class="portal-welcome">
      <div>
        <p class="eyebrow"><?= esc($user['department'] ?? 'Customer portal') ?></p>
        <h1>Hello, <?= esc($user['first_name']) ?></h1>
        <p>Manage your Sun Son Solar services and account.</p>
      </div>
      <a class="button button-outline" href="<?= site_url('change-password') ?>">Change password</a>
    </div>

    <?php if (session('success')): ?><p class="notice success"><?= esc(session('success')) ?></p><?php endif; ?>
    <?php if (session('error')): ?><p class="notice error"><?= esc(session('error')) ?></p><?php endif; ?>
    <?php if (session('errors')): foreach (session('errors') as $error): ?><p class="notice error"><?= esc($error) ?></p><?php endforeach; endif; ?>

    <?php if ($user['role'] === 'admin' && isset($metrics)): ?>
      <section class="metric-grid" aria-label="Business overview">
        <article><span>Customers</span><strong><?= esc((string) $metrics['customers']) ?></strong></article>
        <article><span>Service bookings</span><strong><?= esc((string) $metrics['bookings']) ?></strong></article>
        <article><span>Recorded service revenue</span><strong><?= esc(number_format((float) $metrics['revenue'], 2)) ?></strong></article>
      </section>
    <?php endif; ?>

    <?php if ($user['role'] === 'customer'): ?>
      <section class="panel" id="schedule">
        <div class="panel-heading"><div><p class="eyebrow">Appointments</p><h2>Schedule a service</h2></div></div>
        <?php if ($services !== []): ?>
          <form class="form-grid" action="<?= site_url('dashboard/bookings') ?>" method="post">
            <?= csrf_field() ?>
            <label>Service<select name="service_type" required><?php foreach ($services as $service): ?><option value="<?= esc($service['service_type']) ?>"><?= esc($service['service_type']) ?></option><?php endforeach; ?></select></label>
            <label>Preferred date and time<input type="datetime-local" name="scheduled_at" min="<?= esc(date('Y-m-d\TH:i', time() + 3600)) ?>" required></label>
            <label class="wide">Service address<input type="text" name="address" maxlength="1000" value="<?= esc(old('address', $user['address'])) ?>" required></label>
            <label class="wide">Notes for our team<textarea name="notes" rows="3" maxlength="2000"></textarea></label>
            <button class="button button-primary" type="submit">Request appointment</button>
          </form>
        <?php else: ?>
          <p>Online booking is temporarily unavailable. Please contact our team for help.</p>
        <?php endif; ?>
      </section>

      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Your appointments</p><h2>Service history</h2></div></div>
        <?php if ($bookings === []): ?><p class="empty-state">Your service requests and completed work will appear here.</p><?php else: ?>
          <div class="table-wrap"><table><thead><tr><th>Service</th><th>Date</th><th>Address</th><th>Status</th><th>Payment / receipt</th><th>Actions</th></tr></thead><tbody>
            <?php foreach ($bookings as $booking): ?>
              <tr>
                <td><?= esc($booking['service_type']) ?></td>
                <td><?= esc(date('M j, Y g:i A', strtotime($booking['scheduled_at']))) ?></td>
                <td><?= esc($booking['address']) ?></td>
                <td><span class="status"><?= esc(ucwords(str_replace('_', ' ', $booking['status']))) ?></span></td>
                <td><?php if ($booking['receipt_number']): ?><strong><?= esc(number_format((float) $booking['paid_amount'], 2)) ?></strong><br><span class="receipt-reference"><?= esc($booking['receipt_number']) ?></span><br><span class="receipt-reference"><?= esc($booking['payment_method']) ?> · <?= esc(date('M j, Y', strtotime($booking['paid_at']))) ?></span><?php else: ?>—<?php endif; ?></td>
                <td>
                  <?php if (in_array($booking['status'], ['pending', 'approved'], true)): ?>
                    <form class="inline-form" action="<?= site_url('dashboard/bookings/' . $booking['id'] . '/reschedule') ?>" method="post">
                      <?= csrf_field() ?><label class="sr-only" for="reschedule-<?= esc((string) $booking['id']) ?>">New preferred time</label>
                      <input id="reschedule-<?= esc((string) $booking['id']) ?>" type="datetime-local" name="scheduled_at" min="<?= esc(date('Y-m-d\TH:i', time() + 3600)) ?>" required>
                      <button class="button button-small" type="submit">Reschedule</button>
                    </form>
                    <form class="inline-form" action="<?= site_url('dashboard/bookings/' . $booking['id'] . '/cancel') ?>" method="post">
                      <?= csrf_field() ?><button class="button button-danger button-small" type="submit">Cancel</button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody></table></div>
        <?php endif; ?>
      </section>

      <section class="two-column">
        <article class="panel">
          <div class="panel-heading"><div><p class="eyebrow">Get in touch</p><h2>Support</h2></div></div>
          <form class="form-grid one-column" action="<?= site_url('dashboard/tickets') ?>" method="post">
            <?= csrf_field() ?>
            <label>Subject<input type="text" name="subject" maxlength="150" required></label>
            <label>How can we help?<textarea name="message" rows="4" maxlength="4000" required></textarea></label>
            <button class="button button-primary" type="submit">Send support request</button>
          </form>
          <?php if ($tickets !== []): ?><h3 class="subheading">Previous requests</h3><?php foreach ($tickets as $ticket): ?><p class="list-item"><strong><?= esc($ticket['subject']) ?></strong><span><?= esc(ucfirst($ticket['status'])) ?> · <?= esc(date('M j, Y', strtotime($ticket['created_at']))) ?></span></p><?php endforeach; ?><?php endif; ?>
        </article>
        <article class="panel">
          <div class="panel-heading"><div><p class="eyebrow">Your account</p><h2>Profile</h2></div></div>
          <form class="form-grid one-column" action="<?= site_url('dashboard/profile') ?>" method="post">
            <?= csrf_field() ?>
            <label>First name<input name="first_name" maxlength="50" value="<?= esc($user['first_name']) ?>" required></label>
            <label>Middle name<input name="middle_name" maxlength="50" value="<?= esc($user['middle_name'] ?? '') ?>"></label>
            <label>Last name<input name="last_name" maxlength="50" value="<?= esc($user['last_name']) ?>" required></label>
            <label>Birthdate<input type="date" name="birthdate" value="<?= esc($user['birthdate']) ?>" required></label>
            <label>Gender<input name="gender" maxlength="20" value="<?= esc($user['gender']) ?>" required></label>
            <label>Email<input type="email" name="email" maxlength="100" value="<?= esc($user['email']) ?>" required></label>
            <label>Phone number<input type="tel" name="phone_number" maxlength="20" value="<?= esc($user['phone_number']) ?>" required></label>
            <label>Address<textarea name="address" rows="2" maxlength="1000" required><?= esc($user['address']) ?></textarea></label>
            <button class="button button-primary" type="submit">Save profile</button>
          </form>
          <h3 class="subheading">Notification preferences</h3>
          <form class="form-grid one-column" action="<?= site_url('dashboard/preferences') ?>" method="post">
            <?= csrf_field() ?>
            <label class="checkbox-label"><input type="checkbox" name="service_notifications" value="1" <?= $preferences['service_notifications'] ? 'checked' : '' ?>> Service and account notifications in this portal</label>
            <label class="checkbox-label"><input type="checkbox" name="promotional_notifications" value="1" <?= $preferences['promotional_notifications'] ? 'checked' : '' ?>> Promotional notifications in this portal</label>
            <button class="button button-primary" type="submit">Save preferences</button>
          </form>
        </article>
      </section>
    <?php endif; ?>

    <?php if ($user['role'] === 'technician'): ?>
      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Attendance</p><h2>Workday check-in</h2></div></div>
        <p>Location is used only to record this attendance event.</p>
        <form class="location-form" data-location-form action="<?= site_url($attendance ? 'dashboard/attendance/check-out' : 'dashboard/attendance/check-in') ?>" method="post">
          <?= csrf_field() ?><input type="hidden" name="latitude"><input type="hidden" name="longitude">
          <button class="button button-primary button-large" type="submit"><?= $attendance ? 'Check out' : 'Check in' ?></button>
          <span class="location-message" aria-live="polite"></span>
          <?php if ($attendance): ?><span>Checked in <?= esc(date('M j, Y g:i A', strtotime($attendance['checked_in_at']))) ?></span><?php endif; ?>
        </form>
      </section>

      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Assigned to you</p><h2>My jobs</h2></div></div>
        <?php if ($bookings === []): ?><p class="empty-state">No active jobs are assigned to you.</p><?php else: ?>
          <div class="job-grid">
            <?php foreach ($bookings as $booking): ?>
              <article class="job-card">
                <p class="eyebrow"><?= esc($booking['service_type']) ?></p>
                <h3><?= esc($booking['first_name'] . ' ' . $booking['last_name']) ?></h3>
                <p><?= esc($booking['address']) ?></p>
                <form action="<?= site_url('dashboard/bookings/' . $booking['id'] . '/status') ?>" method="post" class="inline-form">
                  <?= csrf_field() ?><label class="sr-only" for="status-<?= esc((string) $booking['id']) ?>">Update job status</label>
                  <select id="status-<?= esc((string) $booking['id']) ?>" name="status"><option value="en_route">En route</option><option value="in_progress">In progress</option><option value="completed">Completed</option></select>
                  <button class="button button-small" type="submit">Update status</button>
                </form>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>

      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Send to admin</p><h2>Report a problem</h2></div></div>
        <form class="form-grid" action="<?= site_url('dashboard/problems') ?>" method="post">
          <?= csrf_field() ?>
          <label>Issue type<input type="text" name="category" maxlength="80" placeholder="Equipment issue, customer concern..." required></label>
          <label>Related job<select name="booking_id"><option value="0">No specific job</option><?php foreach ($bookings as $booking): ?><option value="<?= esc((string) $booking['id']) ?>"><?= esc($booking['service_type'] . ' · ' . $booking['scheduled_at']) ?></option><?php endforeach; ?></select></label>
          <label class="wide">Details<textarea name="description" rows="4" maxlength="4000" required></textarea></label>
          <button class="button button-primary" type="submit">Send report</button>
        </form>
      </section>
    <?php endif; ?>

    <?php if (in_array($user['role'], ['admin', 'dispatcher'], true)): ?>
      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Service calendar</p><h2>All bookings</h2></div></div>
        <?php if ($bookings === []): ?><p class="empty-state">No service requests yet.</p><?php else: ?>
          <div class="table-wrap"><table><thead><tr><th>Customer</th><th>Service</th><th>Date</th><th>Address</th><th>Status</th><th>Review / assign</th></tr></thead><tbody>
            <?php foreach ($bookings as $booking): ?>
              <tr><td><?= esc($booking['customer_first_name'] . ' ' . $booking['customer_last_name']) ?></td><td><?= esc($booking['service_type']) ?></td><td><?= esc(date('M j, Y g:i A', strtotime($booking['scheduled_at']))) ?></td><td><?= esc($booking['address']) ?></td><td><?= esc(ucwords(str_replace('_', ' ', $booking['status']))) ?><?php if ($booking['receipt_number']): ?><br><span class="receipt-reference">Paid · <?= esc($booking['receipt_number']) ?></span><?php endif; ?></td><td>
                <?php if ($booking['status'] === 'pending'): foreach (['approved' => 'Approve', 'declined' => 'Decline'] as $status => $label): ?>
                  <form class="inline-form" action="<?= site_url('dashboard/bookings/' . $booking['id'] . '/review') ?>" method="post"><?= csrf_field() ?><input type="hidden" name="status" value="<?= esc($status) ?>"><button class="button button-small" type="submit"><?= esc($label) ?></button></form>
                <?php endforeach; endif; ?>
                <?php if (in_array($booking['status'], ['pending', 'approved', 'en_route', 'in_progress'], true)): ?>
                  <form class="inline-form" action="<?= site_url('dashboard/bookings/' . $booking['id'] . '/assign') ?>" method="post"><?= csrf_field() ?><label class="sr-only" for="tech-<?= esc((string) $booking['id']) ?>">Assign technician</label><select id="tech-<?= esc((string) $booking['id']) ?>" name="technician_id" required><option value="">Assign technician</option><?php foreach ($technicians as $tech): ?><option value="<?= esc((string) $tech['id']) ?>"><?= esc($tech['first_name'] . ' ' . $tech['last_name']) ?></option><?php endforeach; ?></select><button class="button button-small" type="submit">Assign</button></form>
                  <form class="inline-form" action="<?= site_url('dashboard/bookings/' . $booking['id'] . '/schedule') ?>" method="post"><?= csrf_field() ?><label class="sr-only" for="schedule-<?= esc((string) $booking['id']) ?>">Adjust booking time</label><input id="schedule-<?= esc((string) $booking['id']) ?>" type="datetime-local" name="scheduled_at" min="<?= esc(date('Y-m-d\TH:i', time() + 3600)) ?>" required><button class="button button-small" type="submit">Adjust time</button></form>
                <?php endif; ?>
                <?php if ($booking['status'] === 'completed' && ! $booking['receipt_number']): ?>
                  <form class="inline-form" action="<?= site_url('dashboard/bookings/' . $booking['id'] . '/payments') ?>" method="post"><?= csrf_field() ?><label class="sr-only" for="amount-<?= esc((string) $booking['id']) ?>">Payment amount</label><input id="amount-<?= esc((string) $booking['id']) ?>" type="number" name="amount" min="0.01" step="0.01" value="<?= esc((string) ($booking['price'] ?? '')) ?>" placeholder="Amount" required><label class="sr-only" for="method-<?= esc((string) $booking['id']) ?>">Payment method</label><select id="method-<?= esc((string) $booking['id']) ?>" name="payment_method"><option>Cash</option><option>Bank Transfer</option><option>Card</option></select><button class="button button-small" type="submit">Record payment</button></form>
                <?php endif; ?>
                <?= esc(trim(($booking['technician_first_name'] ?? '') . ' ' . ($booking['technician_last_name'] ?? ''))) ?>
              </td></tr>
            <?php endforeach; ?>
          </tbody></table></div>
        <?php endif; ?>
      </section>
    <?php endif; ?>

    <?php if (in_array($user['role'], ['admin', 'hr'], true)): ?>
      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Admin &amp; HR</p><h2>Create employee account</h2></div></div>
        <p>Employees cannot self-register. Provide a temporary password; the employee must change it at first login.</p>
        <form class="form-grid" action="<?= site_url('dashboard/employees') ?>" method="post">
          <?= csrf_field() ?>
          <label>First name<input name="first_name" maxlength="50" required></label><label>Middle name<input name="middle_name" maxlength="50"></label>
          <label>Last name<input name="last_name" maxlength="50" required></label><label>Birthdate<input type="date" name="birthdate" required></label>
          <label>Gender<input name="gender" maxlength="20" required></label><label>Email<input type="email" name="email" maxlength="100" required></label>
          <label>Phone number<input type="tel" name="phone_number" maxlength="20" required></label><label>Username<input name="username" maxlength="50" required></label>
          <label>Department<select name="department" required><?php foreach ($departments as $department): ?><option><?= esc($department) ?></option><?php endforeach; ?></select></label>
          <label>Role<select name="role" required><?php foreach ($employeeRoles as $role): ?><option value="<?= esc($role) ?>"><?= esc(ucfirst($role)) ?></option><?php endforeach; ?></select></label>
          <label class="wide">Address<input name="address" maxlength="1000" required></label>
          <label>Temporary password<input type="password" name="password" minlength="12" required></label>
          <button class="button button-primary" type="submit">Create employee</button>
        </form>
      </section>

      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Accounts</p><h2>Employee management</h2></div></div>
        <div class="employee-list">
          <?php foreach ($employees as $employee): ?>
            <details class="employee-item">
              <summary><?= esc($employee['first_name'] . ' ' . $employee['last_name'] . ' · ' . ucfirst($employee['role']) . ' · ' . ($employee['is_active'] ? 'Active' : 'Inactive')) ?></summary>
              <form class="form-grid" action="<?= site_url('dashboard/employees/' . $employee['id']) ?>" method="post">
                <?= csrf_field() ?>
                <label>First name<input name="first_name" value="<?= esc($employee['first_name']) ?>" required></label>
                <label>Middle name<input name="middle_name" value="<?= esc($employee['middle_name'] ?? '') ?>"></label>
                <label>Last name<input name="last_name" value="<?= esc($employee['last_name']) ?>" required></label>
                <label>Birthdate<input type="date" name="birthdate" value="<?= esc($employee['birthdate']) ?>" required></label>
                <label>Gender<input name="gender" value="<?= esc($employee['gender']) ?>" required></label>
                <label>Email<input type="email" name="email" value="<?= esc($employee['email']) ?>" required></label>
                <label>Phone number<input name="phone_number" value="<?= esc($employee['phone_number']) ?>" required></label>
                <label>Department<select name="department"><?php foreach ($departments as $department): ?><option value="<?= esc($department) ?>" <?= $employee['department'] === $department ? 'selected' : '' ?>><?= esc($department) ?></option><?php endforeach; ?></select></label>
                <label>Role<select name="role"><?php foreach ($employeeRoles as $role): ?><option value="<?= esc($role) ?>" <?= $employee['role'] === $role ? 'selected' : '' ?>><?= esc(ucfirst($role)) ?></option><?php endforeach; ?></select></label>
                <label class="wide">Address<input name="address" value="<?= esc($employee['address']) ?>" required></label>
                <button class="button button-primary" type="submit">Save employee changes</button>
              </form>
              <?php if ($employee['is_active']): ?><form action="<?= site_url('dashboard/employees/' . $employee['id'] . '/deactivate') ?>" method="post"><?= csrf_field() ?><button class="button button-danger" type="submit">Deactivate account</button></form><?php endif; ?>
            </details>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <?php if ($user['role'] === 'admin'): ?>
      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Service settings</p><h2>Categories, pricing &amp; availability</h2></div></div>
        <?php foreach ($serviceSettings as $service): ?>
          <form class="settings-row" action="<?= site_url('dashboard/services/' . $service['id']) ?>" method="post">
            <?= csrf_field() ?><label>Service category<input name="service_type" maxlength="50" value="<?= esc($service['service_type']) ?>" required></label>
            <label>Price<input type="number" name="price" min="0" step="0.01" value="<?= esc((string) ($service['price'] ?? '')) ?>" placeholder="Set price"></label>
            <label>Availability<select name="is_available"><option value="1" <?= $service['is_available'] ? 'selected' : '' ?>>Available</option><option value="0" <?= ! $service['is_available'] ? 'selected' : '' ?>>Hidden</option></select></label>
            <button class="button button-small" type="submit">Save</button>
          </form>
        <?php endforeach; ?>
        <form class="settings-row" action="<?= site_url('dashboard/services') ?>" method="post">
          <?= csrf_field() ?><label>New service category<input name="service_type" maxlength="50" required></label>
          <label>Price<input type="number" name="price" min="0" step="0.01" placeholder="Set price"></label>
          <label>Availability<select name="is_available"><option value="1">Available</option><option value="0">Hidden</option></select></label>
          <button class="button button-small" type="submit">Add category</button>
        </form>
      </section>

      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Quote inbox</p><h2>Quote requests</h2></div></div>
        <?php if ($quotes === []): ?><p class="empty-state">No quote requests yet.</p><?php else: foreach ($quotes as $quote): ?>
          <article class="record-card"><div><strong><?= esc($quote['name']) ?></strong><span><?= esc($quote['email']) ?><?= $quote['phone_number'] ? ' · ' . esc($quote['phone_number']) : '' ?> · <?= esc(date('M j, Y', strtotime($quote['created_at']))) ?></span></div><p><?= nl2br(esc($quote['details'])) ?></p><form class="inline-form" action="<?= site_url('dashboard/quotes/' . $quote['id']) ?>" method="post"><?= csrf_field() ?><select name="status"><?php foreach (['new', 'reviewed', 'closed'] as $status): ?><option value="<?= esc($status) ?>" <?= $quote['status'] === $status ? 'selected' : '' ?>><?= esc(ucfirst($status)) ?></option><?php endforeach; ?></select><button class="button button-small" type="submit">Update</button></form></article>
        <?php endforeach; endif; ?>
      </section>

      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Service usage</p><h2>Booking activity</h2></div></div>
        <?php $maximumUsage = $serviceUsage === [] ? 1 : max(array_map(static fn ($entry) => (int) $entry['total'], $serviceUsage)); ?>
        <?php if ($serviceUsage === []): ?><p class="empty-state">Service usage will appear when customers make bookings.</p><?php else: foreach ($serviceUsage as $usage): ?>
          <div class="usage-row"><span><?= esc($usage['service_type']) ?></span><span class="usage-track"><span style="width: <?= esc((string) max(5, (int) round(((int) $usage['total'] / $maximumUsage) * 100))) ?>%"></span></span><strong><?= esc((string) $usage['total']) ?></strong></div>
        <?php endforeach; endif; ?>
      </section>

      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Accountability</p><h2>Recent admin activity</h2></div></div>
        <?php if ($auditLogs === []): ?><p class="empty-state">Admin actions will appear here as they occur.</p><?php else: foreach ($auditLogs as $log): ?>
          <article class="record-card"><strong><?= esc($log['action']) ?> · <?= esc($log['first_name'] . ' ' . $log['last_name']) ?></strong><span><?= esc(date('M j, Y g:i A', strtotime($log['created_at']))) ?></span><p><?= esc($log['details'] ?? '') ?></p></article>
        <?php endforeach; endif; ?>
      </section>

      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Technician reports</p><h2>Problem reports</h2></div></div>
        <?php if ($problems === []): ?><p class="empty-state">No problem reports yet.</p><?php else: foreach ($problems as $problem): ?><article class="record-card"><strong><?= esc($problem['category']) ?> · <?= esc($problem['first_name'] . ' ' . $problem['last_name']) ?></strong><span><?= esc(date('M j, Y g:i A', strtotime($problem['created_at']))) ?></span><p><?= nl2br(esc($problem['description'])) ?></p></article><?php endforeach; endif; ?>
      </section>
    <?php endif; ?>

    <?php if ($user['role'] === 'admin' || $user['role'] === 'hr'): ?>
      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Team updates</p><h2>Send an announcement</h2></div></div>
        <form class="form-grid" action="<?= site_url('dashboard/announcements') ?>" method="post">
          <?= csrf_field() ?>
          <label>Audience<select name="audience"><option value="employees">Employees</option><?php if ($user['role'] === 'admin'): ?><option value="customers">Customers</option><?php endif; ?></select></label>
          <label>Notification type<select name="type"><option value="announcement">Announcement</option><option value="reminder">Reminder</option><?php if ($user['role'] === 'admin'): ?><option value="promotion">Promotion</option><option value="system_update">System update</option><?php endif; ?></select></label>
          <label>Title<input name="title" maxlength="150" required></label><label class="wide">Message<textarea name="message" rows="4" maxlength="4000" required></textarea></label>
          <button class="button button-primary" type="submit">Send to employees</button>
        </form>
      </section>
    <?php endif; ?>

    <?php if ($user['role'] === 'admin'): ?>
      <section class="panel">
        <div class="panel-heading"><div><p class="eyebrow">Customer support</p><h2>Recent support requests</h2></div></div>
        <?php if ($tickets === []): ?><p class="empty-state">No support tickets yet.</p><?php else: foreach ($tickets as $ticket): ?><article class="record-card"><strong><?= esc($ticket['subject']) ?> · <?= esc($ticket['first_name'] . ' ' . $ticket['last_name']) ?></strong><span><?= esc(ucfirst($ticket['status'])) ?> · <?= esc(date('M j, Y', strtotime($ticket['created_at']))) ?></span><p><?= nl2br(esc($ticket['message'])) ?></p></article><?php endforeach; endif; ?>
      </section>
    <?php endif; ?>

    <section class="panel">
      <div class="panel-heading"><div><p class="eyebrow">Updates</p><h2>Notifications &amp; announcements</h2></div></div>
      <?php if ($notifications === []): ?><p class="empty-state">You are all caught up.</p><?php else: foreach ($notifications as $notification): ?><article class="notification"><strong><?= esc($notification['title']) ?></strong><p><?= nl2br(esc($notification['message'])) ?></p><span><?= esc(date('M j, Y g:i A', strtotime($notification['created_at']))) ?></span></article><?php endforeach; endif; ?>
    </section>
  </main>

  <script>
    document.querySelectorAll('[data-location-form]').forEach(function (form) {
      form.addEventListener('submit', function (event) {
        event.preventDefault();
        var message = form.querySelector('.location-message');
        var button = form.querySelector('button[type="submit"]');
        button.disabled = true;
        message.textContent = 'Getting your location...';
        if (!navigator.geolocation) {
          message.textContent = 'Location is unavailable in this browser.';
          button.disabled = false;
          return;
        }
        navigator.geolocation.getCurrentPosition(function (position) {
          form.querySelector('[name="latitude"]').value = position.coords.latitude;
          form.querySelector('[name="longitude"]').value = position.coords.longitude;
          form.submit();
        }, function () {
          message.textContent = 'Location permission is required to record attendance.';
          button.disabled = false;
        }, { enableHighAccuracy: true, timeout: 10000 });
      });
    });
  </script>
</body>
</html>
