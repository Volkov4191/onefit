<?php
namespace UserBundle\Service;

/**
 * Рандомайзер
 * Используется для получения случайных данных
 *
 * Class Randomizer
 * @package UserBundle\Service
 */
class Randomizer implements RandomizerInterface
{
    private $colors = array('#CD0000' => 'Красный', '#FF8C00' => 'Оранжевый', '#FFC814' => 'Жёлтый', '#228B22' => 'Зелёный', '#000080' => 'Синий', '#5A009D' => 'Фиолетовый');
    private $names = array('Ёж', 'Бобр', 'Кенгуру', 'Медведь', 'Бурундук', 'Верблюд', 'Енот', 'Крокодил', 'Орел', 'Слон');

    private $colorId = null;
    private $nameId = null;

    public function __construct(){
        $this->colorId = array_rand($this->colors);
        $this->nameId  = array_rand($this->names);
    }

    /**
     * Получить случайный цвет
     *
     * @return string
     */
    public function getColor(){
        return $this->colorId;
    }

    /**
     * Получить случайную фамилию
     *
     * @return string
     */
    public function getLastName(){
        return $this->colors[$this->colorId];
    }

    /**
     * Получить случайное имя
     *
     * @return string
     */
    public function getFirstName(){
        return $this->names[$this->nameId];
    }
}