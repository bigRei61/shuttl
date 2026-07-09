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
    .calendar-page-section .container { max-width:1180px; }
    .header-section { padding: 18px 0; margin-bottom: 0; border-bottom: 1px solid #4EDFCE; position: sticky; top: 0; z-index: 1000; }
    .calendar-page-section.spad, .calendar-page-section { padding-top: 28px !important; padding-bottom: 28px !important; }
    .calendar-wrap { display:flex; gap:22px; align-items:stretch; margin-bottom:22px; }
    .calendar-main { flex:0 0 64%; max-width:64%; min-height:496px; background:#fff; border:1px solid #d6dee7; padding:30px 32px 26px; }
    .calendar-side { flex:1; min-width:0; display:flex; }
    .calendar-month-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; }
    .calendar-month-title { font-size:22px; font-weight:700; color:#131313; margin:0; }
    .calendar-nav a { display:inline-flex; width:30px; height:30px; align-items:center; justify-content:center; border-radius:50%; background:#e5e5e5; color:#131313; margin-left:7px; font-size:11px; padding:0; }
    .calendar-nav a:hover { background:#4EDFCE; color:#131313; }
    .calendar-grid { width:100%; border-collapse:collapse; }
    .calendar-grid th { font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#131313; text-align:center; padding-bottom:14px; border-bottom:1px solid #d6dee7; }
    .calendar-grid td { text-align:center; vertical-align:middle; padding:6px 0; height:60px; width:14.28%; }
    .day-cell { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:50%; color:#131313; font-size:13px; font-weight:700; transition:all .25s; cursor:pointer; position:relative; }
    .day-cell.muted { color:#c4c9ce; }
    .day-cell.today { box-shadow:0 0 0 2px #E5E7EB; font-weight:700; }
    .day-cell.has-joined-event { background:#4EDFCE; color:#131313; }
    .day-cell.has-host-event { background:#EA7632; color:#fff; }
    .day-cell.has-past-joined-event { background:#DEF3EE; color:#131313; }
    .day-cell.has-past-host-event { background:#FFF0E8; color:#131313; }
    .day-cell.selected { box-shadow:0 0 0 4px #E5E7EB; font-weight:700; z-index:1; }
    .day-cell.has-host-event.selected,
    .day-cell.has-joined-event.selected,
    .day-cell.has-past-host-event.selected,
    .day-cell.has-past-joined-event.selected,
    .day-cell.today.selected { box-shadow:0 0 0 4px #E5E7EB; }
    .day-cell:hover { background:#E5E7EB; color:#131313; }
    .side-widget, .upcoming-widget { background:#fff; border:1px solid #d6dee7; padding:22px 20px; margin-bottom:0; }
    .side-widget { flex:1; height:496px; min-height:496px; overflow:hidden; display:flex; flex-direction:column; }
    .upcoming-widget { padding:26px 22px; }
    .widget-title { color:#131313; font-size:20px; font-weight:700; margin-bottom:18px; }
    .side-widget .widget-title { font-size:18px; margin-bottom:18px; }
    .focus-list, .schedule-list { display:grid; gap:12px; }
    .focus-list { flex:1; min-height:0; align-content:start; overflow-y:auto; padding:4px 4px 2px 0; }
    .focus-list:empty { display:none; }
    .focus-list::-webkit-scrollbar { width:6px; }
    .focus-list::-webkit-scrollbar-thumb { background:#d6dee7; border-radius:999px; }
    .focus-event, .schedule-item { display:flex; flex-direction:column; justify-content:space-between; min-height:98px; min-width:0; background:#f8fbfb; border:1px solid #d6dee7; color:#131313; padding:14px 16px; border-radius:8px; text-decoration:none; transition:all .2s; }
    .focus-event { min-height:124px; justify-content:flex-start; background:#fff; }
    .focus-event.joined-card { border-color:#cbece7; }
    .focus-event.host-card { border-color:#f1bc9e; }
    .schedule-item { min-height:112px; }
    .schedule-item.joined-card { background:#f4fffc; border-color:#92e5d8; }
    .schedule-item.host-card { background:#fff0e8; border-color:#EA7632; }
    .focus-event:hover, .schedule-item:hover { border-color:#4EDFCE; color:#131313; text-decoration:none; transform:translateY(-1px); }
    .focus-event.joined-card:hover, .schedule-item.joined-card:hover { border-color:#4EDFCE; }
    .focus-event.host-card:hover, .schedule-item.host-card:hover { border-color:#EA7632; }
    .event-card-top { display:flex; align-items:flex-start; justify-content:space-between; gap:10px; margin-bottom:10px; min-width:0; }
    .focus-event .event-card-top { align-items:center; background:#DEF3EE; border-radius:999px; margin:-2px 0 14px; padding:9px 12px; }
    .focus-event.host-card .event-card-top { background:#fff0e8; }
    .event-card-date { color:#131313; display:block; font-size:11px; font-weight:700; min-width:0; overflow:hidden; text-overflow:ellipsis; text-transform:uppercase; white-space:nowrap; }
    .event-card-title { color:#131313; font-size:15px; font-weight:700; line-height:1.3; margin:0 0 7px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .event-card-location { color:#626262; font-size:12px; line-height:1.4; margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .role-pill { display:inline-flex; align-items:center; flex-shrink:0; gap:5px; border-radius:999px; color:#131313; font-size:10px; font-weight:700; line-height:1; max-width:50%; overflow:hidden; padding:6px 10px; text-overflow:ellipsis; text-transform:uppercase; white-space:nowrap; }
    .role-pill::before { content:""; width:6px; height:6px; border-radius:50%; background:currentColor; opacity:.62; }
    .role-pill.host { background:#EA7632; color:#fff; }
    .role-pill.joined { background:#4EDFCE; color:#131313; }
    .upcoming-widget .schedule-list { grid-template-columns:repeat(var(--upcoming-count, 1), minmax(0, 1fr)); gap:12px; }
    .upcoming-widget .schedule-list.is-empty { grid-template-columns:1fr; }
    .no-event-message { font-size:14px; color:#878787; margin-top:8px; }
    @media (max-width: 991px) {
        .calendar-wrap { flex-direction:column; }
        .calendar-main, .calendar-side { flex:1 1 auto; max-width:100%; width:100%; }
        .side-widget { height:auto; max-height:496px; min-height:0; }
        .focus-list { overflow-y:auto; padding-right:4px; }
        .upcoming-widget .schedule-list { grid-template-columns:1fr; }
    }

    /* Match the shared Events/Tournament hero height while keeping this page's local alignment overrides. */
    section.page-info-section.set-bg {
        height: 499px !important;
        min-height: 499px !important;
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
						<h4 class="widget-title" id="date-focus-title">Date's Focus</h4>
						<div class="focus-list" id="today-focus-list"></div>
						<p id="no-event-message" class="no-event-message" style="display:none;">No events on this date.</p>
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
			const serverEvents = @json($events);
			const roleColors = @json($roleColors);
			const today = startOfDay(new Date());
			let currentMonth = new Date(today.getFullYear(), today.getMonth(), 1);
			let selectedDate = new Date(today);

			function dateFromYMD(ymd) {
				if (!ymd) return null;
				const parts = String(ymd).split('-');
				return new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]));
			}

			function startOfDay(date) {
				return new Date(date.getFullYear(), date.getMonth(), date.getDate());
			}

			function eventStart(event) {
				return dateFromYMD(event.start_date);
			}

			function eventEnd(event) {
				return dateFromYMD(event.end_date);
			}

			function eventCoversDate(event, date) {
				const day = startOfDay(date);
				return day >= eventStart(event) && day <= eventEnd(event);
			}

			function getEventsForDate(date) {
				return serverEvents
					.filter(event => eventCoversDate(event, date))
					.sort((a, b) => {
						if (a.role !== b.role) return a.role === 'host' ? -1 : 1;
						return eventStart(a) - eventStart(b) || String(a.title).localeCompare(String(b.title));
					});
			}

			function formatDate(date) {
				return date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
			}

			function formatShortDate(date) {
				return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
			}

			function formatFocusTitleDate(date) {
				return date.toLocaleDateString('en-US', { month: 'long', day: 'numeric' });
			}

			function formatEventDateRange(event) {
				const start = eventStart(event);
				const end = eventEnd(event);

				if (isSameDay(start, end)) {
					return formatShortDate(start);
				}

				return `${formatShortDate(start)} - ${formatShortDate(end)}`;
			}

			function escapeHTML(value) {
				return String(value ?? '')
					.replace(/&/g, '&amp;')
					.replace(/</g, '&lt;')
					.replace(/>/g, '&gt;')
					.replace(/"/g, '&quot;')
					.replace(/'/g, '&#039;');
			}

			function roleLabel(role) {
				return role === 'host' ? 'Host' : 'Joined';
			}

			function rolePill(event) {
				const role = event.role === 'host' ? 'host' : 'joined';
				const color = roleColors[role] || (role === 'host' ? '#EA7632' : '#4EDFCE');

				return `<span class="role-pill ${role}" style="background:${color};">${roleLabel(role)}</span>`;
			}

			function createEventCard(event, className, showDate) {
				const item = document.createElement('a');
				const role = event.role === 'host' ? 'host' : 'joined';
				item.href = event.url;
				item.className = `${className} ${role}-card`;
				item.innerHTML = `
					<div class="event-card-top">
						${showDate ? `<span class="event-card-date">${escapeHTML(formatEventDateRange(event))}</span>` : '<span></span>'}
						${rolePill(event)}
					</div>
					<h6 class="event-card-title">${escapeHTML(event.title)}</h6>
					<p class="event-card-location">${escapeHTML(event.location || '')}</p>
				`;

				return item;
			}

			function isSameDay(a, b) {
				return a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
			}

			function isPastDay(date) {
				return startOfDay(date) < today;
			}

			const monthTitle = document.getElementById('calendar-month-title');
			const gridBody = document.getElementById('calendar-grid-body');
			const noEventMessage = document.getElementById('no-event-message');
			const scheduleList = document.getElementById('schedule-list');
			const dateFocusTitle = document.getElementById('date-focus-title');
			const todayFocusList = document.getElementById('today-focus-list');

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
							const hasHostEvent = eventsForDay.some(event => event.role === 'host');
							const dayRole = hasHostEvent ? 'host' : 'joined';
							span.classList.add(isPastDay(cell.date) ? `has-past-${dayRole}-event` : `has-${dayRole}-event`);
						}
						if (isSameDay(cell.date, selectedDate)) span.classList.add('selected');
						span.textContent = cell.day;
						span.addEventListener('click', () => {
							selectedDate = new Date(cell.date);
							renderCalendar();
						});
						td.appendChild(span);
						tr.appendChild(td);
					}
					gridBody.appendChild(tr);
				}

				renderDateFocus();
				renderUpcomingEvents();
			}

			function renderDateFocus() {
				const selectedDateEvents = getEventsForDate(selectedDate);
				dateFocusTitle.textContent = `${formatFocusTitleDate(selectedDate)} Events`;
				todayFocusList.innerHTML = '';

				if (!selectedDateEvents.length) {
					noEventMessage.style.display = 'block';
					noEventMessage.textContent = 'No events on this date.';

					return;
				}

				noEventMessage.style.display = 'none';
				selectedDateEvents.forEach(event => {
					todayFocusList.appendChild(createEventCard(event, 'focus-event', true));
				});
			}

			function renderUpcomingEvents() {
				scheduleList.innerHTML = '';
				const upcomingEvents = serverEvents
					.filter(event => eventStart(event) > today)
					.sort((a, b) => eventStart(a) - eventStart(b) || String(a.title).localeCompare(String(b.title)))
					.slice(0, 3);
				const count = upcomingEvents.length;

				scheduleList.style.setProperty('--upcoming-count', count || 1);
				scheduleList.classList.toggle('is-empty', count === 0);

				if (!count) {
					const empty = document.createElement('p');
					empty.className = 'no-event-message';
					empty.textContent = 'No upcoming events after today.';
					scheduleList.appendChild(empty);

					return;
				}

				upcomingEvents.forEach(event => {
					scheduleList.appendChild(createEventCard(event, 'schedule-item', true));
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
