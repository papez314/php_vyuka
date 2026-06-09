<?php
// Zapneme zachytávání obsahu stránky
ob_start(function($buffer) {
    // Definujeme vzhled nového tlačítka, které zapadne do vašeho designu
    $tlacitkoAdmin = '<li><a href="admin.php" style="background: #1e3f28; color: #fff; border-radius: 4px; padding: 5px 10px; font-weight: bold;">⚙️ Administrace</a></li>';
    
    // Vložíme tlačítko do hlavního menu na každé stránce těsně před zavírací značku </ul>
    return str_replace('</ul>', $tlacitkoAdmin . '</ul>', $buffer);
});