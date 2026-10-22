<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', 'Home::index');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::attemptRegister');
$routes->post('quotes', 'Home::requestQuote');
$routes->get('change-password', 'Auth::changePassword');
$routes->post('change-password', 'Auth::savePassword');
$routes->post('logout', 'Auth::logout');

$routes->get('dashboard', 'Dashboard::index');
$routes->post('dashboard/bookings', 'Dashboard::bookService');
$routes->post('dashboard/bookings/(:num)/cancel', 'Dashboard::cancelBooking/$1');
$routes->post('dashboard/bookings/(:num)/reschedule', 'Dashboard::rescheduleBooking/$1');
$routes->post('dashboard/bookings/(:num)/schedule', 'Dashboard::adjustSchedule/$1');
$routes->post('dashboard/bookings/(:num)/review', 'Dashboard::reviewBooking/$1');
$routes->post('dashboard/bookings/(:num)/assign', 'Dashboard::assignTechnician/$1');
$routes->post('dashboard/bookings/(:num)/status', 'Dashboard::updateJobStatus/$1');
$routes->post('dashboard/bookings/(:num)/payments', 'Dashboard::recordPayment/$1');
$routes->post('dashboard/employees', 'Dashboard::createEmployee');
$routes->post('dashboard/employees/(:num)', 'Dashboard::updateEmployee/$1');
$routes->post('dashboard/employees/(:num)/deactivate', 'Dashboard::deactivateEmployee/$1');
$routes->post('dashboard/attendance/check-in', 'Dashboard::checkIn');
$routes->post('dashboard/attendance/check-out', 'Dashboard::checkOut');
$routes->post('dashboard/problems', 'Dashboard::reportProblem');
$routes->post('dashboard/announcements', 'Dashboard::sendAnnouncement');
$routes->post('dashboard/services/(:num)', 'Dashboard::updateService/$1');
$routes->post('dashboard/services', 'Dashboard::createService');
$routes->post('dashboard/quotes/(:num)', 'Dashboard::updateQuote/$1');
$routes->post('dashboard/tickets', 'Dashboard::createTicket');
$routes->post('dashboard/profile', 'Dashboard::updateProfile');
$routes->post('dashboard/preferences', 'Dashboard::updatePreferences');
