<?php
// Zapneme zachytávání obsahu stránky
ob_start(function($buffer) {
    // Definujeme vzhled nového tlačítka, které zapadne do vašeho designu
    $tlacitkoAdmin = '<li><a href="admin.php" class="u-admin-link">⚙️ Administrace</a></li>';
    
    // Vložíme tlačítko do hlavního menu na každé stránce těsně před zavírací značku </ul>
    return str_replace('</ul>', $tlacitkoAdmin . '</ul>', $buffer);
});