require('../css/app.scss');

import { Modal } from "bootstrap";

require('../../vendor/schulit/common-bundle/Resources/assets/js/polyfill');
require('../../vendor/schulit/common-bundle/Resources/assets/js/menu');
require('../../vendor/schulit/common-bundle/Resources/assets/js/dropdown-polyfill');

document.addEventListener('DOMContentLoaded', function() {

    document.querySelector('form[name=bid]')?.addEventListener('submit', event => {
        event.preventDefault();

        let $form = document.querySelector('form[name=bid]');
        let $modal = document.getElementById('confirm-bid');
        let $confirm = $modal.querySelector('button.confirm');

        $confirm.addEventListener('click', event => {
            $form.submit();
        });

        let $bid = $modal.querySelector('.bid');
        $bid.innerHTML = document.getElementById('bid_amount').value;

        let modal = Modal.getOrCreateInstance($modal);
        $modal.addEventListener('hidden.bs.modal', event => {
            $confirm.click = null;
        });
        modal.show();
    })
});
