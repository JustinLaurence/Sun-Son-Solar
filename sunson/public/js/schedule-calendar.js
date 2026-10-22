(function () {
  var calendar = document.querySelector('[data-service-calendar]');
  if (!calendar) {
    return;
  }

  var templates = Array.from(calendar.querySelectorAll('template[data-booking-id]'));
  var eventsByDate = new Map();
  var templatesById = new Map();
  var daysElement = calendar.querySelector('[data-calendar-days]');
  var titleElement = calendar.querySelector('[data-calendar-title]');
  var emptyElement = calendar.querySelector('[data-calendar-empty]');
  var dialog = calendar.querySelector('[data-booking-dialog]');
  var detailsElement = calendar.querySelector('[data-booking-details]');
  var visibleMonth = new Date();
  visibleMonth.setDate(1);

  function dateKey(date) {
    return [
      date.getFullYear(),
      String(date.getMonth() + 1).padStart(2, '0'),
      String(date.getDate()).padStart(2, '0')
    ].join('-');
  }

  templates.forEach(function (template) {
    var scheduledAt = template.dataset.scheduledAt || '';
    var match = scheduledAt.match(/^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})/);

    if (!match) {
      console.error('Skipping a booking with an invalid scheduled date.', template.dataset.bookingId);
      return;
    }

    var date = new Date(
      Number(match[1]),
      Number(match[2]) - 1,
      Number(match[3]),
      Number(match[4]),
      Number(match[5])
    );
    var key = dateKey(date);
    var event = {
      id: template.dataset.bookingId,
      customer: template.dataset.customer || '',
      service: template.dataset.serviceType || '',
      status: template.dataset.status || 'pending',
      date: date
    };

    if (!eventsByDate.has(key)) {
      eventsByDate.set(key, []);
    }
    eventsByDate.get(key).push(event);
    templatesById.set(event.id, template);
  });

  function renderCalendar() {
    titleElement.textContent = new Intl.DateTimeFormat(undefined, {
      month: 'long',
      year: 'numeric'
    }).format(visibleMonth);
    daysElement.replaceChildren();

    var firstDay = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth(), 1);
    var gridStart = new Date(firstDay);
    gridStart.setDate(firstDay.getDate() - firstDay.getDay());
    var today = dateKey(new Date());
    var visibleMonthKey = visibleMonth.getMonth();
    var monthHasEvents = false;

    for (var dayOffset = 0; dayOffset < 42; dayOffset++) {
      var date = new Date(gridStart);
      date.setDate(gridStart.getDate() + dayOffset);
      var key = dateKey(date);
      var dayEvents = (eventsByDate.get(key) || []).slice().sort(function (left, right) {
        return left.date - right.date;
      });
      var cell = document.createElement('div');
      cell.className = 'calendar-day';
      cell.setAttribute('role', 'gridcell');

      if (date.getMonth() !== visibleMonthKey) {
        cell.classList.add('calendar-day-outside');
      } else if (dayEvents.length > 0) {
        monthHasEvents = true;
      }
      if (key === today) {
        cell.classList.add('calendar-day-today');
      }

      var dateLabel = document.createElement('time');
      dateLabel.className = 'calendar-date';
      dateLabel.dateTime = key;
      dateLabel.textContent = String(date.getDate());
      cell.appendChild(dateLabel);

      dayEvents.forEach(function (event) {
        var eventButton = document.createElement('button');
        eventButton.type = 'button';
        eventButton.className = 'calendar-event calendar-event-' + event.status.replace(/[^a-z0-9_-]/gi, '');
        eventButton.setAttribute('aria-label', [
          event.service,
          event.customer,
          event.date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' }),
          event.status.replace(/_/g, ' ')
        ].filter(Boolean).join(', '));
        eventButton.dataset.bookingId = event.id;

        var eventTime = document.createElement('span');
        eventTime.className = 'calendar-event-time';
        eventTime.textContent = event.date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        eventButton.appendChild(eventTime);

        var eventService = document.createElement('span');
        eventService.className = 'calendar-event-service';
        eventService.textContent = event.service;
        eventButton.appendChild(eventService);

        var eventCustomer = document.createElement('span');
        eventCustomer.className = 'calendar-event-customer';
        eventCustomer.textContent = event.customer;
        eventButton.appendChild(eventCustomer);

        eventButton.addEventListener('click', function () {
          var template = templatesById.get(event.id);
          if (!template) {
            console.error('Booking details are unavailable for calendar event.', event.id);
            return;
          }

          detailsElement.replaceChildren(template.content.cloneNode(true));
          if (typeof dialog.showModal === 'function') {
            dialog.showModal();
          } else {
            dialog.setAttribute('open', '');
          }
        });
        cell.appendChild(eventButton);
      });

      daysElement.appendChild(cell);
    }

    emptyElement.hidden = monthHasEvents;
  }

  calendar.querySelector('[data-calendar-previous]').addEventListener('click', function () {
    visibleMonth.setMonth(visibleMonth.getMonth() - 1);
    renderCalendar();
  });
  calendar.querySelector('[data-calendar-next]').addEventListener('click', function () {
    visibleMonth.setMonth(visibleMonth.getMonth() + 1);
    renderCalendar();
  });
  calendar.querySelector('[data-calendar-today]').addEventListener('click', function () {
    visibleMonth = new Date();
    visibleMonth.setDate(1);
    renderCalendar();
  });
  calendar.querySelector('[data-calendar-close]').addEventListener('click', function () {
    if (typeof dialog.close === 'function') {
      dialog.close();
    } else {
      dialog.removeAttribute('open');
    }
  });

  renderCalendar();
}());
