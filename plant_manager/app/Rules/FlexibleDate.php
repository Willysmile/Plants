<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FlexibleDate implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Skip if empty (nullable)
        }

        $dateStr = trim($value);
        $parts = explode('/', $dateStr);

        $day = null;
        $month = null;
        $year = null;

        // Format: dd/mm/yyyy (3 parts)
        if (count($parts) === 3) {
            $day = (int) $parts[0];
            $month = (int) $parts[1];
            $year = (int) $parts[2];
        }
        // Format: mm/yyyy (2 parts)
        elseif (count($parts) === 2) {
            $day = 15; // Default day
            $month = (int) $parts[0];
            $year = (int) $parts[1];
        }
        else {
            $fail('Le format de la date est invalide. Utilisez jj/mm/aaaa ou mm/aaaa.');
            return;
        }

        // Validate month
        if ($month < 1 || $month > 12) {
            $fail('Le mois doit être entre 1 et 12.');
            return;
        }

        // Validate day if full format
        if (count($parts) === 3) {
            if ($day < 1 || $day > 31) {
                $fail('Le jour doit être entre 1 et 31.');
                return;
            }
        }

        // Create date - validate it's not in the future
        $date = \DateTime::createFromFormat('d/m/Y', "$day/$month/$year");
        
        if (!$date) {
            $fail('La date n\'existe pas.');
            return;
        }

        // Check if date is not in the future
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        
        if ($date > $today) {
            $fail('La date d\'achat ne peut pas être future.');
            return;
        }
    }
}
