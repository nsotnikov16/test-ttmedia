<?php
if ($arParams['USE_FILTER'] === 'Y'): ?>
    <?
    $APPLICATION->IncludeComponent(
        "nsotnikov:currency.filter",
        "",
        array(
            'COLUMNS' => $arParams['COLUMNS']
        )
    );
    ?>
<?php endif; ?>

<? if (!empty($arResult['ITEMS'])): ?>
    <table class="table">
        <thead>
            <tr>
                <?php foreach ($arParams['COLUMNS'] as $column): ?>
                    <th><?= GetMessage('COLUMN_' . $column) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <tr>
                    <?php foreach ($arParams['COLUMNS'] as $column): ?>
                        <td><?= htmlspecialchars($item[$column]) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<? else: ?>
    <p>Ничего не найдено</p>
<? endif; ?>

<?php
// Навигация
$APPLICATION->IncludeComponent(
    "bitrix:main.pagenavigation",
    "",
    [
        "NAV_OBJECT" => $arResult["NAV"],
        "SEF_MODE" => "N",
    ],
    false
);
?>