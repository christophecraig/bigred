const openOrderConfirmation = (date, time) => {
    const formattedDate = new Date(date).toLocaleDateString('en-nz');
    document.getElementById('order-modal').classList.add('is-active');
    document.getElementById('modal-content').innerHTML = `
    <div class="box has-text-centered">
        <form action="/orders/new" method="POST">
            <p>You want to order a delivery on the <strong>${formattedDate}</strong> in the <strong>${time}</strong>.
            </p>
            <p>Is that ok ?</p>
            <input type="hidden" name="date" value="${date}">
            <input type="hidden" name="time" value="${time}">
            <button onclick="closeModal()" class="button is-danger">Cancel</button>
            <button type="submit" class="button is-success">Confirm</button>
        </form>
    </div>`
}

const closeModal = () => {
    document.getElementById('order-modal').classList.remove('is-active');
}

const morningOrders = orders.filter(order => order.deliveryTime === 'morning');
const afternoonOrders = orders.filter(order => order.deliveryTime === 'afternoon');
const ordersDates = {
    'morning': morningOrders,
    'afternoon': afternoonOrders,
};
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    const today = new Date();

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'en-nz',
        datesRender: function (info) {
            for (time in ordersDates) {
                for (date in ordersDates[time]) {
                    if (ordersDates[time][date] > 4) {
                        let cell = document.querySelector('[data-date="' + date + '"] .' + time);
                        if (cell) {
                            cell.style = "background-color: red!important; cursor: unset; margin-bottom: 0!important;";
                            cell.onclick = "";
                        }
                    }
                }
            }
        },
        dayRender: function (dayRenderInfo) {
            const date = dayRenderInfo.el.getAttribute('data-date');
            // If date is past
            if (new Date(date) < today) {
                dayRenderInfo.el.innerHTML = `
                        <div class="tile" style="height: 100%;">
                            <div class="tile is-ancestor" style="height: 100%;">
                                <div class="tile is-vertical is-parent" style="height: 100%;">
                                    <div class="tile is-child morning columns is-centered is-vcentered has-background-grey" style="margin-bottom: 0!important; cursor: unset;">
                                        <p>Morning</p>
                                    </div>
                                    <div class="tile is-child afternoon columns is-centered is-vcentered has-background-grey" style="cursor: unset";>
                                        <p>Afternoon</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
            } else {
                dayRenderInfo.el.innerHTML = `
                        <div class="tile" style="height: 100%;">
                            <div class="tile is-ancestor" style="height: 100%;">
                                <div class="tile is-vertical is-parent" style="height: 100%;">
                                    <div class="tile is-child morning columns is-centered is-vcentered has-background-grey-light" onclick="openOrderConfirmation('${date}', 'morning')" style="margin-bottom: 0!important">
                                        <p>Morning</p>
                                    </div>
                                    <div class="tile is-child afternoon columns is-centered is-vcentered has-background-grey-lighter" onclick="openOrderConfirmation('${date}', 'afternoon')">
                                        <p>Afternoon</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
            }
        },
        plugins: ['dayGrid', 'timeGrid']
    });

    calendar.render();
});