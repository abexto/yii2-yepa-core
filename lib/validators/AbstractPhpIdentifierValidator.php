<?php

/*
 * Copyright (c) 2015, Andreas Prucha, Abexto (Helicon Software Development)
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, 
 * are permitted provided that the following conditions are met:
 * 
 * *  Redistributions of source code must retain the above copyright notice, this 
 *    list of conditions and the following disclaimer.
 * *  Redistributions in binary form must reproduce the above copyright notice, 
 *    this list of conditions and the following disclaimer in the documentation 
 *    and/or other materials provided with the distribution.
 * *  Neither the name of Abexto, Helicon Software Development, Andreas Prucha
 *    nor the names of its contributors may be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND 
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. 
 * IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, 
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, 
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF 
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE 
 * OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED 
 * OF THE POSSIBILITY OF SUCH DAMAGE.
 */

namespace abexto\yepa\core\validators;

/**
 * Validates a Class Name
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 */
abstract class AbstractPhpIdentifierValidator extends \yii\validators\RegularExpressionValidator
{

    /**
     * It's forbidden and an error is set in case of rule violation.
     */
    const CHECK_FORBIDDEN = 'forbidden';

    /**
     * It's allowed 
     */
    const CHECK_ALLOWED = 'allowed';

    /**
     * It's required and an error is set in case of rule violation
     */
    const CHECK_REQUIRED = 'required';

    /**
     * It's required, but corrected automatically
     */
    const CHECK_ADDED = 'added';

    /**
     * It's not allowed, but corrected automatically
     */
    const CHECK_REMOVED = 'removed';

    /**
     * @var string  Basic validation pattern 
     */
    public $pattern = '/^[\w\\\\]*$/';

    /**
     * 
     * @var bool    Identifier may include namespaces
     */
    public $allowNamespace = true;

    /**
     * Specfies how to handle leading namespace separators
     *  
     * @var int    Specifies if the Identifer may or must begin with a namespace separator 
     */
    public $leadingNamespaceSeparator = self::CHECK_ALLOWED;

    protected function validateNsSeparator(&$value, $pos, $mode)
    {
        $result = null;
        if (!empty($value)) {
            $fnd = substr_compare($value, '\\', $pos, 1) === 0;
            if ($fnd) {
                switch ($mode) {
                    case self::CHECK_FORBIDDEN:
                        $result = ['Namespace separator required at {pos, select, =0{begin} =-1{end} other{position {pos}}', ['pos', $pos]];
                        break;
                    case self::CHECK_REMOVED:
                        switch ($pos) {
                            case 0:
                                $value = ltrim($value, '\\');
                                break;
                            case -1:
                                $value = ltrim($value, '\\');
                                break;
                            default:
                                $value = substr_replace($value, '', $pos, 1);
                        }
                        break;
                }
            } else {
                switch ($mode) {
                    case self::CHECK_REQUIRED:
                        $result = ['Namespace separator required at {pos, select, =0{begin} =-1{end} other{position {pos}}', ['pos', $pos]];
                        break;
                    case self::CHECK_ADDED:
                        switch ($pos) {
                            case 0:
                                $value = substr_replace($value, '\\', 0, 0);
                                break;
                            case -1:
                                $value .= '\\';
                        }
                }
            }
        }
        return $result;
    }

    protected function internalValidateValue(&$value)
    {
        $result = parent::validateValue($value);
        if (empty($result)) {
            if ($this->allowNamespace) {
                $result = $this->validateNsSeparator($value, 0, $this->leadingNamespaceSeparator);
            } else {
                if (!$this->allowNamespace && !$model->hasErrors()) {
                    if (strpos($model->$attribute, '\\') !== FALSE) {
                        $result = ['Namespace may not be specified', null];
                    }
                }
            }
        }
        return $result;
    }

    protected function validateValue($value)
    {
        return $this->internalValidateValue($value);
    }

    public function validateAttribute($model, $attribute)
    {
        $originalValue = $model->$attribute;
        $value = $originalValue;
        $result = $this->internalValidateValue($value);
        if ($originalValue !== $value) {
            $model->$attribute = $value;
        }
        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
    }

}
