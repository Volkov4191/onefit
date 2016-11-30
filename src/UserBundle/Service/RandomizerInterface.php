<?php

namespace UserBundle\Service;

/**
 * Интерфейс для генерации случайных данных
 *
 * Interface iRandomizer
 * @package UserBundle\Service
 */
interface RandomizerInterface
{
    /**
     * Получить случайный цвет
     *
     * @return string
     */
    public function getColor();

    /**
     * Получить случайную фамилию
     *
     * @return string
     */
    public function getLastName();

    /**
     * Получить случайное имя
     *
     * @return string
     */
    public function getFirstName();
}