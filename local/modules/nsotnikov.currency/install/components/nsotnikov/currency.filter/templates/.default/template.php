<? if (!empty($arParams['COLUMNS'])): ?>
    <form class="form filter p-4 border rounded my-3">
        <? foreach ($arParams['COLUMNS'] as $key => $column) : ?>
            <? $className = 'field-' . $column; ?>
            <div class="row mb-3">
                <label for="field-<?= $key ?>" class="form-label col-3"><?= GetMessage('COLUMN_' . $column) ?></label>
                <? if (in_array($column, ['DATE', 'COURSE'])): ?>
                    <? $type = $column === 'DATE' ? 'datetime-local' : 'text' ?>
                    <input type="<?= $type ?>" class="form-control col me-2 <?= $className ?>" id="field-<?= $key ?>" name="FILTER[>=<?= $column ?>]" value="<?= $_GET['FILTER']['>=' . $column] ?? '' ?>" placeholder="<?= GetMessage('FILTER_FIELD_FROM') ?>">
                    <input type="<?= $type ?>" class="form-control col <?= $className ?>" name="FILTER[<=<?= $column ?>]" value="<?= $_GET['FILTER']['<=' . $column] ?? '' ?>" placeholder="<?= GetMessage('FILTER_FIELD_TO') ?>">
                <? else: ?>
                    <input type="text" class="form-control col <?= $className ?>" id="field-<?= $key ?>" name="FILTER[<?= $column ?>]" value="<?= $_GET['FILTER'][$column] ?? '' ?>">
                <? endif; ?>
            </div>
        <? endforeach ?>
        <div class="row justify-content-center">
            <input type="submit" value="Применить" class="btn btn-primary filter__btn me-2">
            <a href="<?= $APPLICATION->GetCurPage(); ?>" class="btn btn-secondary filter__btn">Сбросить</a>
           <!--  <input type="submit" value="Сбросить" class="btn btn-secondary filter__btn"> -->
        </div>
    </form>
<? endif; ?>