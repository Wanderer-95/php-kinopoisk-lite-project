<?php

declare(strict_types=1);

namespace Kernel\Validator;

/**
 * Validator supporting rules:
 *  - required
 *  - min, max (strings, numbers, arrays)
 *  - email
 *  - password
 *  - confirmation
 *
 * Usage:
 *  $rules = [
 *      'email' => ['required', 'email'],
 *      'password' => 'required|password|min:8|confirmation',
 *      'age' => 'min:18|max:99',
 *  ];
 *
 *  $validator = new Validator();
 *  $bool = $validator->validate(array $data, array $rules)
 *  if ($validator->validate($data)) {
 *      echo "OK";
 *  } else {
 *      print_r($validator->errors());
 *  }
 */
class Validator implements ValidatorInterface
{
    /** @var array<string, array<int, array{rule:string, params:array<int,string>}>> */
    private array $parsedRules = [];

    /** @var array<string, list<string>> */
    private array $errors = [];

    /** @var array<string, string> */
    private array $messages;

    /**
     * @param array<string, string|array<int,string>> $rules
     * @param array<string, string> $customMessages
     */
    public function __construct(array $customMessages = [])
    {
        $this->messages = $this->defaultMessages();
        foreach ($customMessages as $key => $msg) {
            $this->messages[$key] = $msg;
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public function validate(array $data, array $rules): bool
    {
        $this->parseRules($rules);
        $this->errors = [];
        $this->run($data);
        return empty($this->errors);
    }

    /** @return array<string, list<string>> */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * @param array<string, string|array<int,string>> $rules
     */
    private function parseRules(array $rules): void
    {
        foreach ($rules as $field => $ruleSet) {
            $items = is_array($ruleSet) ? $ruleSet : explode('|', (string) $ruleSet);
            foreach ($items as $r) {
                $r = trim($r);
                if ($r === '') { continue; }
                $name = $r;
                $params = [];
                if (str_contains($r, ':')) {
                    [$name, $paramStr] = explode(':', $r, 2);
                    $params = array_map('trim', explode(',', $paramStr));
                }
                $this->parsedRules[$field][] = [
                    'rule' => strtolower($name),
                    'params' => $params,
                ];
            }
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function run(array $data): void
    {
        foreach ($this->parsedRules as $field => $rules) {
            $valueExists = array_key_exists($field, $data);
            $value = $valueExists ? $data[$field] : null;

            $isEmpty = !$valueExists || $this->isEmpty($value);
            foreach ($rules as $rule) {
                $name = $rule['rule'];
                $params = $rule['params'];

                if ($name !== 'required' && $isEmpty) {
                    if ($name === 'confirmation' && $valueExists) {
                        $this->validateConfirmation($data, $field, $value);
                    }
                    continue;
                }

                switch ($name) {
                    case 'required':
                        $this->validateRequired($field, $valueExists ? $value : null);
                        break;
                    case 'min':
                        $this->validateMin($field, $value, $params);
                        break;
                    case 'max':
                        $this->validateMax($field, $value, $params);
                        break;
                    case 'email':
                        $this->validateEmail($field, $value);
                        break;
                    case 'password':
                        $this->validatePassword($field, (string) $value, $params);
                        break;
                    case 'confirmation':
                        $this->validateConfirmation($data, $field, $value);
                        break;
                    default:
                        $this->addError($field, $this->message('unknown_rule', $field, [$name]));
                        break;
                }
            }
        }
    }

    private function validateRequired(string $field, mixed $value): void
    {
        if ($this->isEmpty($value)) {
            $this->addError($field, $this->message('required', $field));
        }
    }

    /** @param list<string> $params */
    private function validateMin(string $field, mixed $value, array $params): void
    {
        $n = isset($params[0]) ? (int) $params[0] : 0;
        if (is_array($value)) {
            if (count($value) < $n) {
                $this->addError($field, $this->message('min.array', $field, [$n]));
            }
        } elseif (is_numeric($value)) {
            if ((float) $value < $n) {
                $this->addError($field, $this->message('min.numeric', $field, [$n]));
            }
        } else {
            $len = $this->strlen((string) $value);
            if ($len < $n) {
                $this->addError($field, $this->message('min.string', $field, [$n]));
            }
        }
    }

    /** @param list<string> $params */
    private function validateMax(string $field, mixed $value, array $params): void
    {
        $n = isset($params[0]) ? (int) $params[0] : PHP_INT_MAX;
        if (is_array($value)) {
            if (count($value) > $n) {
                $this->addError($field, $this->message('max.array', $field, [$n]));
            }
        } elseif (is_numeric($value)) {
            if ((float) $value > $n) {
                $this->addError($field, $this->message('max.numeric', $field, [$n]));
            }
        } else {
            $len = $this->strlen((string) $value);
            if ($len > $n) {
                $this->addError($field, $this->message('max.string', $field, [$n]));
            }
        }
    }

    private function validateEmail(string $field, mixed $value): void
    {
        $v = (string) $value;
        if ($v === '' || filter_var($v, FILTER_VALIDATE_EMAIL) === false) {
            $this->addError($field, $this->message('email', $field));
        }
    }

    /**
     * password[:lower,upper,digit,symbol,min=N]
     * Defaults: lower,upper,digit,symbol,min=8
     *
     * @param list<string> $params
     */
    private function validatePassword(string $field, string $value, array $params): void
    {
        $needLower = true;
        $needUpper = true;
        $needDigit = true;
        $needSymbol = true;
        $min = 8;

        foreach ($params as $p) {
            $p = strtolower($p);
            if ($p === 'lower') { $needLower = true; }
            elseif ($p === 'upper') { $needUpper = true; }
            elseif ($p === 'digit') { $needDigit = true; }
            elseif ($p === 'symbol') { $needSymbol = true; }
            elseif (str_starts_with($p, 'min=')) { $min = max(1, (int) substr($p, 4)); }
        }

        if ($this->strlen($value) < $min) {
            $this->addError($field, $this->message('password.min', $field, [$min]));
        }
        if ($needLower && !preg_match('/[a-z]/u', $value)) {
            $this->addError($field, $this->message('password.lower', $field));
        }
        if ($needUpper && !preg_match('/[A-Z]/u', $value)) {
            $this->addError($field, $this->message('password.upper', $field));
        }
        if ($needDigit && !preg_match('/\d/u', $value)) {
            $this->addError($field, $this->message('password.digit', $field));
        }
        if ($needSymbol && !preg_match('/[^\p{L}\p{N}\s]/u', $value)) {
            $this->addError($field, $this->message('password.symbol', $field));
        }
    }

    private function validateConfirmation(array $data, string $field, mixed $value): void
    {
        $confirmationField = $field . '_confirmation';
        $expected = $data[$confirmationField] ?? null;
        if ($value !== $expected) {
            $this->addError($field, $this->message('confirmation', $field));
        }
    }

    private function isEmpty(mixed $value): bool
    {
        if ($value === null) return true;
        if (is_string($value)) return trim($value) === '';
        if (is_array($value)) return count($value) === 0;
        return false;
    }

    private function strlen(string $value): int
    {
        return function_exists('mb_strlen') ? (int) mb_strlen($value, 'UTF-8') : strlen($value);
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field] ??= [];
        $this->errors[$field][] = $message;
    }

    private function message(string $key, string $field, array $repl = []): string
    {
        $template = $this->messages[$key] ?? $key;
        $msg = str_replace(':attribute', $field, $template);
        foreach ($repl as $i => $val) {
            $msg = str_replace(':param'.($i+1), (string)$val, $msg);
        }
        return $msg;
    }

    /** @return array<string, string> */
    private function defaultMessages(): array
    {
        return [
            'required' => 'Поле :attribute обязательно для заполнения.',
            'min.string' => 'Длина поля :attribute должна быть не меньше :param1 символов.',
            'min.numeric' => 'Значение поля :attribute должно быть не меньше :param1.',
            'min.array' => 'Количество элементов в :attribute должно быть не меньше :param1.',
            'max.string' => 'Длина поля :attribute должна быть не больше :param1 символов.',
            'max.numeric' => 'Значение поля :attribute должно быть не больше :param1.',
            'max.array' => 'Количество элементов в :attribute должно быть не больше :param1.',
            'email' => 'Поле :attribute должно быть корректным email-адресом.',
            'password.min' => 'Пароль (:attribute) должен содержать не менее :param1 символов.',
            'password.lower' => 'Пароль (:attribute) должен содержать хотя бы одну строчную букву.',
            'password.upper' => 'Пароль (:attribute) должен содержать хотя бы одну заглавную букву.',
            'password.digit' => 'Пароль (:attribute) должен содержать хотя бы одну цифру.',
            'password.symbol' => 'Пароль (:attribute) должен содержать хотя бы один символ.',
            'confirmation' => 'Поле :attribute не совпадает с подтверждением.',
            'unknown_rule' => 'Неизвестное правило ":param1" для поля :attribute.',
        ];
    }
}
