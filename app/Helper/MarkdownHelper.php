// app/Helpers/MarkdownHelper.php

<?php

namespace App\Helpers;

use Parsedown;

class MarkdownHelper
{
    public static function convertToHtml($markdown)
    {
        $parsedown = new Parsedown();
        return $parsedown->text($markdown);
    }
}
