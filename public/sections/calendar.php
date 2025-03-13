<div class="calendar-wrapper">
    <header>
        <p class="current-date"></p>
        <div class="icons">
            <i id="prev" class="fas fa-chevron-left"></i>
            <i id="next" class="fas fa-chevron-right"></i>

            <!-- <span id="prev">chevron_left</span>
            <span id="next" class="material-symbols-rounded">chevron_right</span> -->
        </div>
    </header>
    <div class="calendar">
        <ul class="weeks">
            <li>Sun</li>
            <li>Mon</li>
            <li>Tue</li>
            <li>Wed</li>
            <li>Thu</li>
            <li>Fri</li>
            <li>Sat</li>
        </ul>
        <ul class="days"></ul>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    .calendar-wrapper {
        width: 300px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        position: absolute;
        display: none;
    }

    .calendar-wrapper.calendar-active {
        display: block;
    }

    .calendar-wrapper header {
        display: flex;
        align-items: center;
        padding: 0.5em 1em;
        justify-content: space-between;
    }

    header .icons {
        display: flex;
    }

    header .icons i {
        width: 1em;
        aspect-ratio: 1/1;
        cursor: pointer;
        color: var(--font-dark);
        text-align: center;
        font-size: 1rem;
        user-select: none;
        border-radius: 50%;
    }

    .icons i:last-child {
        margin-right: -10px;
    }


    header .current-date {
        color: var(--font-dark);
        font-size: var(--body);
        font-weight: 500;
    }

    .calendar {
        padding: 0.5em;
    }

    .calendar ul {
        display: flex;
        flex-wrap: wrap;
        list-style: none;
        text-align: center;
    }

    .calendar .days {
        margin-bottom: 10px;
    }

    .calendar li {
        color: #333;
        width: calc(100% / 7);
        font-size: var(--small);
    }

    .calendar .weeks li {
        font-weight: 500;
        cursor: default;
    }

    .calendar .days li {
        z-index: 1;
        cursor: pointer;
        position: relative;
        margin-top: 1em;
    }

    .days li.inactive {
        color: #aaa;
    }

    .days li.active {
        color: #fff;
    }

    .days li::before {
        position: absolute;
        content: "";
        left: 50%;
        top: 50%;
        height: 30px;
        width: 30px;
        z-index: -1;
        border-radius: 50%;
        transform: translate(-50%, -50%);
    }

    .days li.active::before {
        background: #9B59B6;
    }

    .days li:not(.active, .disabled):hover::before {
        background: #f2f2f2;
    }

    .days li.disabled::before {
        background: #6f6f71;
    }
</style>