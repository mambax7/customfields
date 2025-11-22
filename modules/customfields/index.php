<?php
/**
 * İlave Alanlar Modülü - Ana Sayfa
 * Bu modülün kullanıcı tarafında bir arayüzü yoktur.
 * Tüm işlemler admin panelinden yapılır.
 */

include '../../mainfile.php';
redirect_header(XOOPS_URL . '/admin.php?fct=modulesadmin&op=update&module=customfields', 2, 'Bu modül sadece admin panelinden kullanılır.');
