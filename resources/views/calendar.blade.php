<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Calendar - Shuttl</title>
	<link rel="stylesheet" href="{{ asset('landing/css/bootstrap.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('landing/css/font-awesome.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('landing/css/owl.carousel.css') }}" />
	<link rel="stylesheet" href="{{ asset('landing/css/style.css') }}" />
	<style>
    /* Use landing header styles; don't override header layout here */
    /* Embedded calendar styles adapted from provided template */
    .calendar-page-section { background-color: #eef2f6; border-top: 1px solid #d6dee7; border-bottom: 1px solid #d6dee7; }
    .header-section { padding: 18px 0; margin-bottom: 0; border-bottom: 1px solid #4EDFCE; position: sticky; top: 0; z-index: 1000; }
    .calendar-page-section.spad, .calendar-page-section { padding-top: 20px !important; padding-bottom: 0 !important; }
    .calendar-wrap { display:flex; gap:30px; align-items:flex-start; }
    .calendar-main { flex:0 0 64%; max-width:64%; background:#fff; border:1px solid #d6dee7; padding:38px 38px 28px; }
    .calendar-side { flex:1; min-width:0; }
    .calendar-month-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:30px; }
    .calendar-month-title { font-size:24px; font-weight:500; color:#131313; margin:0; }
    .calendar-nav a { display:inline-block; width:33px; height:33px; text-align:center; padding-top:7px; border-radius:50%; background:#e5e5e5; color:#131313; margin-left:8px; font-size:13px; }
    .calendar-nav a:hover { background:#4EDFCE; color:#131313; }
    .calendar-grid { width:100%; border-collapse:collapse; }
    .calendar-grid th { font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#131313; text-align:center; padding-bottom:18px; border-bottom:1px solid #d6dee7; }
    .calendar-grid td { text-align:center; vertical-align:middle; padding:8px 0; height:66px; width:14.28%; }
    .day-cell { display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:50%; color:#131313; font-size:14px; font-weight:500; transition:all .25s; cursor:pointer; position:relative; }
    .day-cell.muted { color:#c4c9ce; }
    .day-cell.today { background:#d8d8d8; color:#131313; border:1px solid #d8d8d8; }
    .day-cell.today.featured { background:#fff; color:#131313; border:2px solid #4EDFCE; }
    .day-cell.has-event { background:#D7F5EF; color:#131313; border:1px solid #D7F5EF; }
    .day-cell.joined-event { background:#E0426A; color:#fff; border:1px solid #E0426A; }
    .day-cell.featured { border:2px solid #4EDFCE; font-weight:700; background:#fff; color:#131313; z-index:1; }
    .day-cell:hover { background:#4EDFCE; color:#131313; }
    .side-widget, .upcoming-widget { background:#fff; border:1px solid #d6dee7; padding:30px 26px; margin-bottom:30px; }
    .widget-title { font-size:18px; margin-bottom:25px; }
    .event-link { display:flex; align-items:center; justify-content:space-between; background:#eef2f6; border:1px solid #d6dee7; color:#131313; padding:13px 18px; border-radius:30px; margin-bottom:12px; font-size:14px; font-weight:500; text-decoration:none; }
    .event-link:hover { background:#4EDFCE; border-color:#4EDFCE; color:#131313; }
    .event-detail-card { background:#eef2f6; border-left:3px solid #ff205f; padding:20px; margin-bottom:12px; }
    .no-event-message { font-size:14px; color:#878787; margin-top:8px; }

    /* Force-reduce hero height, overriding external style.css rules */
    section.page-info-section.set-bg {
        height: 260px !important;
        min-height: 260px !important;
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
    }
    section.page-info-section.set-bg .pi-content {
        padding: 0 !important;
        margin: 0 !important;	
        width: 100% !important;
    }
</style>
</head>
<body>
	<div id="preloder">
		<div class="loader"></div>
	</div>

	@include('partials.header')

	<section class="page-info-section set-bg" data-setbg="{{ asset('page-top-bg/2.png') }}">
		<div class="pi-content">
			<div class="container">
				<div class="row">
					<div class="col-xl-5 col-lg-6 text-white">
						<h2>Calendar</h2>
						<p>Stay on top of upcoming badminton events and plan your week.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Calendar page section -->
	<section class="page-section calendar-page-section spad">
		<div class="container">
			<div class="calendar-wrap">

				<!-- Calendar grid -->
				<div class="calendar-main">
					<div class="calendar-month-row">
						<h3 class="calendar-month-title" id="calendar-month-title">Loading calendar…</h3>
						<div class="calendar-nav">
							<a href="#" class="calendar-nav-btn" data-direction="-1" aria-label="Previous month"><i class="fa fa-angle-left"></i></a>
							<a href="#" class="calendar-nav-btn" data-direction="1" aria-label="Next month"><i class="fa fa-angle-right"></i></a>
						</div>
					</div>
					<table class="calendar-grid">
						<thead>
							<tr>
								<th>Sun</th>
								<th>Mon</th>
								<th>Tue</th>
								<th>Wed</th>
								<th>Thur</th>
								<th>Fri</th>
								<th>Sat</th>
							</tr>
						</thead>
						<tbody id="calendar-grid-body"></tbody>
					</table>
				</div>

				<!-- Sidebar -->
				<div class="calendar-side">
					<div class="side-widget">
						<h4 class="widget-title">Today's Focus</h4>
						<div class="event-detail-card" id="event-detail-card">
							<h6 id="selected-date-label">Loading…</h6>
							<p id="selected-date-title">Select a day to view upcoming events.</p>
							<p class="strong" id="selected-date-location">Arena West</p>
							<p id="selected-date-time">6:00pm - 8:00pm</p>
							<div class="event-detail-meta">
								<span id="selected-date-meta">Today</span>
								<span id="selected-date-range">Live session</span>
							</div>
						</div>
						<div id="today-min-list"></div>
						<p id="no-event-message" class="no-event-message" style="display:none;">No event scheduled for this day.</p>
						<div id="events-list-container" style="display:none;">
							<div id="events-list-toggle">0 events — click to expand</div>
							<div id="events-list"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="upcoming-widget">
				<h4 class="widget-title">Upcoming Events</h4>
				<div class="schedule-list" id="schedule-list"></div>
			</div>
		</div>
	</section>

	<footer class="footer-section">
		<div class="container">
			<ul class="footer-menu">
				<li><a href="{{ route('landing') }}">Home</a></li>
				<li><a href="{{ route('events.index') }}">Events</a></li>
				<li><a href="{{ route('calendar') }}">Calendar</a></li>
				<li><a href="{{ route('history') }}">Statistics</a></li>
				<li><a href="{{ route('events.index') }}">Tournament</a></li>
			</ul>
			<p class="copyright"> Copyright &copy;{{ date('Y') }} Shuttl. All rights reserved </p>
		</div>
	</footer>

	<script src="{{ asset('landing/js/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('landing/js/owl.carousel.min.js') }}"></script>
	<script src="{{ asset('landing/js/jquery.marquee.min.js') }}"></script>
	<script src="{{ asset('landing/js/main.js') }}"></script>
	<script>
		(function () {
			// Server-provided events
			const serverEvents = @json($events);
			const today = new Date();
			let currentMonth = new Date(today.getFullYear(), today.getMonth(), 1);
			let selectedDate = new Date(today);
			let openEventIndex = null;
			let openEventForDateKey = null;

			function dateFromYMD(ymd) {
				if (!ymd) return null;
				const parts = String(ymd).split('-');
				return new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
			}

			function getEventsForDate(date) {
				const key = date.getFullYear() + '-' + (date.getMonth()+1).toString().padStart(2,'0') + '-' + date.getDate().toString().padStart(2,'0');
				return serverEvents.filter(ev => ev.date === key).map(ev => ({
					title: ev.title,
					type: ev.title,
					location: ev.location || '',
					time: ev.time || '',
					status: ev.status || 'Upcoming'
				}));
			}

			function formatDate(date) {
				return date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
			}

			function isSameDay(a, b) {
				return a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
			}

			const monthTitle = document.getElementById('calendar-month-title');
			const gridBody = document.getElementById('calendar-grid-body');
			const selectedDateLabel = document.getElementById('selected-date-label');
			const selectedDateTitle = document.getElementById('selected-date-title');
			const selectedDateLocation = document.getElementById('selected-date-location');
			const selectedDateTime = document.getElementById('selected-date-time');
			const selectedDateMeta = document.getElementById('selected-date-meta');
			const selectedDateRange = document.getElementById('selected-date-range');
			const eventDetailCard = document.getElementById('event-detail-card');
			const noEventMessage = document.getElementById('no-event-message');
			const scheduleList = document.getElementById('schedule-list');
			const minListContainer = document.getElementById('today-min-list');

			function renderCalendar() {
				const year = currentMonth.getFullYear();
				const month = currentMonth.getMonth();
				monthTitle.textContent = currentMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

				const firstDay = new Date(year, month, 1);
				const lastDay = new Date(year, month + 1, 0);
				const firstWeekDay = firstDay.getDay();
				const totalDays = lastDay.getDate();
				const prevMonthLastDay = new Date(year, month, 0).getDate();

				const days = [];
				for (let i = firstWeekDay; i > 0; i--) {
					days.push({ day: prevMonthLastDay - i + 1, muted: true, date: new Date(year, month - 1, prevMonthLastDay - i + 1) });
				}
				for (let day = 1; day <= totalDays; day++) {
					days.push({ day, muted: false, date: new Date(year, month, day) });
				}
				while (days.length % 7 !== 0) {
					const nextDay = days.length - firstWeekDay - totalDays + 1;
					days.push({ day: nextDay, muted: true, date: new Date(year, month + 1, nextDay) });
				}

				gridBody.innerHTML = '';
				for (let row = 0; row < days.length / 7; row++) {
					const tr = document.createElement('tr');
					for (let col = 0; col < 7; col++) {
						const cell = days[row * 7 + col];
						const td = document.createElement('td');
						const span = document.createElement('span');
						span.className = 'day-cell';
						if (cell.muted) span.classList.add('muted');
						if (isSameDay(cell.date, today) && month === today.getMonth() && year === today.getFullYear()) span.classList.add('today');
						const eventsForDay = getEventsForDate(cell.date);
						if (eventsForDay.length) {
							if (eventsForDay.some(event => event.status === 'Joined')) {
								span.classList.add('joined-event');
							} else {
								span.classList.add('has-event');
							}
						}
						if (isSameDay(cell.date, selectedDate)) span.classList.add('featured');
						span.textContent = cell.day;
						span.addEventListener('click', () => {
							selectedDate = new Date(cell.date);
							renderCalendar();
							renderDetails();
						});
						td.appendChild(span);
						tr.appendChild(td);
					}
					gridBody.appendChild(tr);
				}

				renderDetails();
			}

			function parseStartToDate(day, timeRange) {
				const part = (timeRange || '').split('-')[0].trim();
				const m = part.match(/(\d{1,2})(?::(\d{2}))?\s*(am|pm)/i);
				if (!m) return new Date(day.getFullYear(), day.getMonth(), day.getDate(), 0, 0);
				let hh = parseInt(m[1], 10);
				const mm = m[2] ? parseInt(m[2], 10) : 0;
				const ampm = m[3].toLowerCase();
				if (ampm === 'pm' && hh < 12) hh += 12;
				if (ampm === 'am' && hh === 12) hh = 0;
				return new Date(day.getFullYear(), day.getMonth(), day.getDate(), hh, mm);
			}

			function renderDetails() {
				selectedDateLabel.textContent = formatDate(selectedDate);
				const events = getEventsForDate(selectedDate).map(ev => ({ ...ev, startDate: parseStartToDate(selectedDate, ev.time) }));

				if (!events.length) {
					eventDetailCard.style.display = 'none';
					noEventMessage.style.display = 'block';
					noEventMessage.textContent = 'No event scheduled for this day.';
					selectedDateMeta.textContent = '';
					selectedDateRange.textContent = '';
					if (minListContainer) minListContainer.innerHTML = '';
				} else {
					events.sort((a, b) => a.startDate - b.startDate);

					const dateKey = `${selectedDate.getFullYear()}-${selectedDate.getMonth()}-${selectedDate.getDate()}`;
					if (openEventForDateKey !== dateKey || openEventIndex === null || openEventIndex >= events.length) {
						const joinedIndex = events.findIndex(ev => ev.status === 'Joined');
						openEventIndex = joinedIndex >= 0 ? joinedIndex : 0;
						openEventForDateKey = dateKey;
					}

					if (minListContainer) minListContainer.innerHTML = '';

					events.forEach((ev, idx) => {
						const div = document.createElement('div');
						const isActive = idx === openEventIndex;
						div.className = 'today-min-item' + (ev.status === 'Joined' ? ' joined' : '') + (isActive ? ' active' : '');
						div.innerHTML = `
							<div class="today-min-item-main">
								<div><span class="time">${ev.time.split('-')[0].trim()}</span><span class="name">${ev.title}</span></div>
								<div class="schedule-pill">${ev.status === 'Joined' ? 'Joined' : ''}</div>
							</div>
							<div class="event-inline-details">
								<div><strong>${ev.type}</strong></div>
								<div>${ev.location}</div>
								<div>${ev.time}</div>
							</div>
						`;
						div.addEventListener('click', () => { openEventIndex = idx; renderDetails(); });
						if (minListContainer) minListContainer.appendChild(div);
					});

					if (openEventIndex !== null && events[openEventIndex]) {
						eventDetailCard.style.display = 'block';
						noEventMessage.style.display = 'none';
						selectedDateTitle.textContent = events[openEventIndex].title;
						selectedDateLocation.textContent = events[openEventIndex].location || '';
						selectedDateTime.textContent = events[openEventIndex].time || '';
						selectedDateMeta.textContent = events[openEventIndex].status || '';
						selectedDateRange.textContent = '';
					} else {
						eventDetailCard.style.display = 'none';
						noEventMessage.style.display = 'block';
						noEventMessage.textContent = 'Select an event to view details.';
					}
				}

				// populate bottom schedule list with the next three upcoming events
				scheduleList.innerHTML = '';
				const upcomingEvents = serverEvents
					.map(ev => ({ ...ev, dateObj: dateFromYMD(ev.date) }))
					.filter(ev => ev.dateObj && ev.dateObj >= today)
					.sort((a, b) => a.dateObj - b.dateObj)
					.slice(0, 3);

				upcomingEvents.forEach(event => {
					const item = document.createElement('div');
					const classes = ['schedule-item'];
					if (event.status === 'Live Now') classes.push('live');
					if (event.status === 'Joined') classes.push('joined');
					item.className = classes.join(' ');
					item.innerHTML = `
						<div class="schedule-top">
							<span>${event.dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}</span>
							<span class="schedule-pill">${event.status}</span>
						</div>
						<h6>${event.title}</h6>
						<p>${event.location || ''}</p>
						<p>${event.time || ''}</p>`;
					scheduleList.appendChild(item);
				});
			}

			document.querySelectorAll('.calendar-nav-btn').forEach(button => {
				button.addEventListener('click', (e) => {
					e.preventDefault();
					const direction = Number(button.getAttribute('data-direction'));
					currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + direction, 1);
					selectedDate = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1);
					renderCalendar();
				});
			});

			renderCalendar();
		})();
	</script>
</body>
</html>
