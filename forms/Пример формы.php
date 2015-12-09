<div class="modal-window-close">&times;</div>
<div class="modal form-wrapper">
    <div class="modalTitleWrap">
        <div class="modalTitle">
            Хотите уточнить наличие материала по цветам или стоимость доставки?
        </div>
        <div>
            — Мы перезвоним вам
        </div>
    </div>
    <form class="js-ajax-form" target="/api/forms.call/">
        <label class="labelBlock">
            <span>На какой номер перезвонить?<span class="red">*</span></span>
            <div class="numberPrefix">
                <input type="text" name="phone" class="js-input-text js-form-phone-field">
                <div class="errorText js-error-text"></div>
            </div>
        </label>
        <label class="labelBlock nameContainer js-form-name-container">
            <span>Кого спросить?<span class="red">*</span></span>
            <input type="text" name="fio" class="js-input-text">
            <div class="errorText js-error-text"></div>
        </label>
        <button class="button">Жду звонка</button>
    </form>
</div>