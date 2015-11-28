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
 * Description of ClassNameValidator
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development
 */
class PhpClassNameValidator extends AbstractPhpIdentifierValidator
{

    /**
     * Whether the entered class name must exist  
     * 
     * @var string|null    
     */
    public $classExists = self::CHECK_ALLOWED;

    /**
     * Specifies the required base class
     * 
     * If the given class exists, it must extend the specified class.
     * 
     * @var null    Class Name, or null
     */
    public $extends = null;

    protected function internalValidateClassExists(&$value)
    {
        $result = null;
        // Check if it is allowed that the class already exists or not exists
        if ($this->classExists && $this->classExists !== self::CHECK_ALLOWED) {
            if (class_exists(\abexto\yepa\core\helpers\Utils::normalizeIdentifierNs($value, true, false))) {
                if ($this->classExists == self::CHECK_FORBIDDEN) {
                    $result = ['Class {className} already exists', ['className' => $value]];
                }
            } else {
                if ($this->classExists == self::CHECK_REQUIRED) {
                    $result = ['Class {className} does not exist', ['className' => $value]];
                }
            }
        }
        return $result;
    }

    protected function internalValidateExtends(&$value)
    {
        if ($this->extends) {
            $ncn = \abexto\yepa\core\helpers\Utils::normalizeIdentifierNs($value, true, false);
            if (class_exists($ncn) &&
               !is_subclass_of($ncn, $this->extends)) {
                return ['Class must extend class {extends}', ['extends' => $this->extends]];
            }
        } else {
            return null;
        }
    }

    public function internalValidateValue(&$value)
    {
        $result = parent::internalValidateValue($value);
        if (!$result) {
            $result = $this->internalValidateClassExists($value);
        }
        if (!$result) {
            $result = $this->internalValidateExtends($value);
        }
        return $result;
    }
}
    