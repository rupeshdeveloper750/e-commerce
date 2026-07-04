

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import ApexCharts from 'apexcharts';

window.ApexCharts = ApexCharts;


window.openModal = function (id) {

    const modal = document.getElementById(id);

    if (!modal) return;

    modal.classList.remove('hidden');

    modal.classList.add('flex');

    document.body.classList.add('overflow-hidden');

}

window.closeModal = function (id) {

    const modal = document.getElementById(id);

    if (!modal) return;

    modal.classList.remove('flex');

    modal.classList.add('hidden');

    document.body.classList.remove('overflow-hidden');

}

document.addEventListener('keydown', function (e) {

    if (e.key === 'Escape') {

        document.querySelectorAll('[id$="Modal"]').forEach(modal => {

            modal.classList.remove('flex');

            modal.classList.add('hidden');

        });

        document.body.classList.remove('overflow-hidden');

    }

});
