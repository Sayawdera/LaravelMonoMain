<?php 


namespace App\Traits;

use Illuminate\Support\{Arr, Str};
use Illuminate\Support\Facades\Lang;

trait Translatable
{

    /**
     * ================================================================
     * ParseStringToTranslationParameters()
     * ================================================================
     * Парсит строку и преобразует её в массив параметров для перевода.
     *
     * @param string $String 
     * @return array 
     */
    public function ParseStringToTranslationParameters(string $String): array
    {
        $StringParts = explode(':', Str::after($String, '::'));
        $Prefix = Str::contains($String, '::') ? Str::before($String, '::').'::' : '::';
        $Name = $Prefix.array_shift($StringParts);
        $Attribtues = [];

        if (!empty($StringParts))
        {
            foreach (explode('|', $StringParts[0]) as $KV)
            {
                if ($KV == '')
                {
                    continue;
                }
                $Parts = explode('=', $KV);
                $Attribtues[$Parts[0]] = $Parts[1] ?? '';
            }
        }
        return compact('name', 'attributes');
    }


    /**
     * ================================================================
     * TransformToTranslatableString()
     * ================================================================
     * Преобразует имя и атрибуты в строку, готовую для перевода.
     *
     * @param string $Name
     * @param array $Attributes 
     * @return string 
     */
    public function TransformToTranslatableString(string $Name, array $Attributes = []): string
    {
        if (empty($Attributes) || !Arr::isAssoc($Attributes))
        {
            return $Name;
        }
        return trim($Name.':'.http_build_query(Arr::dot($Attributes), '', '|'), '=:|');
    }


    /**
     * ================================================================
     * GetTranslatedStringArray()
     * ================================================================
     * Возвращает массив с переведенной строкой и ключом, основанным на сообщении.
     *
     * @param string $Message
     * @param array $Attributes
     * @param string|null $Prefix 
     * @return array 
     */
    public function GetTranslatedStringArray(string $Message, array $Attributes = [], ?string $Prefix = null): array
    {
        $Path = !empty($Prefix) ? "{$Prefix}.{$Message}" : $Message;
        $Key = NULL;
        $TranslatedMessage = __($Path, $Attributes);

        if (!Str::startsWith($TranslatedMessage, $Path))
        {
            $Message = $TranslatedMessage;
            $Key = Str::slug(last(explode(':', $Path)), '-');
        }
        return ['Key' => $Key, 'Message' => $Message];
    }


    /**
     * ================================================================
     * IsTranslationKeys()
     * ================================================================
     * Проверяет наличие ключа перевода в языковых файлах.
     *
     * @param string $Key 
     * @return bool 
     */
    public function IsTranslationKeys(string $Key): bool
    {
        if (Lang::has($Key))
        {
            return TRUE;
        }

        if (count($Parts = explode('::', $Key)) == 1)
        {
            return Lang::has(Str::before($Parts[0], ':'));
        }
        return Lang::has($Parts[0].'::'.Str::before($Parts[0], ':'));
    }
}