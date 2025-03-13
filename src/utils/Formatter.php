<?php
/**
 * Utility for formatting dates, numbers, and other values
 */
class Formatter {
    /**
     * Format date to Brazilian format (DD/MM/YYYY)
     */
    public static function formatDate($date) {
        if (empty($date)) {
            return '';
        }
        
        if ($date instanceof DateTime) {
            return $date->format('d/m/Y');
        }
        
        $timestamp = is_numeric($date) ? $date : strtotime($date);
        return date('d/m/Y', $timestamp);
    }
    
    /**
     * Format date and time to Brazilian format (DD/MM/YYYY HH:MM)
     */
    public static function formatDateTime($date) {
        if (empty($date)) {
            return '';
        }
        
        if ($date instanceof DateTime) {
            return $date->format('d/m/Y H:i');
        }
        
        $timestamp = is_numeric($date) ? $date : strtotime($date);
        return date('d/m/Y H:i', $timestamp);
    }
    
    /**
     * Format time (HH:MM)
     */
    public static function formatTime($time) {
        if (empty($time)) {
            return '';
        }
        
        if ($time instanceof DateTime) {
            return $time->format('H:i');
        }
        
        if (strpos($time, ':') !== false) {
            // Already in HH:MM format
            $parts = explode(':', $time);
            return sprintf('%02d:%02d', $parts[0], $parts[1]);
        }
        
        $timestamp = is_numeric($time) ? $time : strtotime($time);
        return date('H:i', $timestamp);
    }
    
    /**
     * Format currency (R$)
     */
    public static function formatCurrency($value) {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
    
    /**
     * Format weight (kg)
     */
    public static function formatWeight($value) {
        return number_format($value, 1, ',', '.') . ' kg';
    }
    
    /**
     * Format height (cm)
     */
    public static function formatHeight($value) {
        return number_format($value, 0, ',', '.') . ' cm';
    }
    
    /**
     * Format percentage
     */
    public static function formatPercentage($value) {
        return number_format($value, 1, ',', '.') . '%';
    }
    
    /**
     * Format calories
     */
    public static function formatCalories($value) {
        return number_format($value, 0, ',', '.') . ' kcal';
    }
    
    /**
     * Format macro nutrients (g)
     */
    public static function formatNutrient($value) {
        return number_format($value, 1, ',', '.') . ' g';
    }
    
    /**
     * Convert date from Brazilian format (DD/MM/YYYY) to database format (YYYY-MM-DD)
     */
    public static function dateToDatabase($date) {
        if (empty($date)) {
            return null;
        }
        
        // Check if already in YYYY-MM-DD format
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }
        
        $parts = explode('/', $date);
        if (count($parts) !== 3) {
            return null;
        }
        
        return sprintf('%04d-%02d-%02d', $parts[2], $parts[1], $parts[0]);
    }
    
    /**
     * Convert time from HH:MM format to database format
     */
    public static function timeToDatabase($time) {
        if (empty($time)) {
            return null;
        }
        
        // Already in correct format, just ensure it has seconds
        $parts = explode(':', $time);
        if (count($parts) === 2) {
            return $time . ':00';
        }
        
        return $time;
    }
    
    /**
     * Get day of week name in Portuguese
     */
    public static function getDayOfWeekName($dayNumber) {
        $days = [
            0 => 'Domingo',
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado'
        ];
        
        return isset($days[$dayNumber]) ? $days[$dayNumber] : '';
    }
    
    /**
     * Get meal type name in Portuguese
     */
    public static function getMealTypeName($mealType) {
        $types = [
            'breakfast' => 'Café da manhã',
            'lunch' => 'Almoço',
            'dinner' => 'Jantar',
            'snack' => 'Lanche'
        ];
        
        return isset($types[$mealType]) ? $types[$mealType] : $mealType;
    }
    
    /**
     * Get activity level name in Portuguese
     */
    public static function getActivityLevelName($level) {
        $levels = [
            'sedentary' => 'Sedentário',
            'light' => 'Levemente ativo',
            'moderate' => 'Moderadamente ativo',
            'active' => 'Ativo',
            'very_active' => 'Muito ativo'
        ];
        
        return isset($levels[$level]) ? $levels[$level] : $level;
    }
    
    /**
     * Get gender name in Portuguese
     */
    public static function getGenderName($gender) {
        $genders = [
            'male' => 'Masculino',
            'female' => 'Feminino',
            'other' => 'Outro'
        ];
        
        return isset($genders[$gender]) ? $genders[$gender] : $gender;
    }
}