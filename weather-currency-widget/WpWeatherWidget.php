<?php

/*
Plugin Name: Weather&Currency Widget
Description: Display the temperature and currency
Author: v.gomonets
Version: 0.1.0
*/

class WpWeatherCurrencyWidget extends WP_Widget
{

    function __construct()
    {

        load_plugin_textdomain('weather_currency_widget');

        parent::__construct(
            'weather_currency_widget',
            __('Weather Widget', 'weather_currency_widget'),
            array('description' => __('Display the temperature and currency', 'weather_currency_widget'))
        );
    }

    function widget($args, $instance)
    {
        ?>
        <p>Currency: 1 USD = &nbsp;<?= $this->getUSD() ?> UAH</p>
        <p>Temperature:&nbsp;<?= $this->getWeather() ?>°C</p>
        <?php
    }

    function getUSD()
    {
        $json = file_get_contents('https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?valcode=USD&json');
        $result = json_decode($json);
        return round($result[0]->rate, 2);
    }

    function getWeather()
    {
        $json = file_get_contents('http://api.openweathermap.org/data/2.5/weather?q=Kiev,ua&APPID=19dfe2ead25a7cbe030a165c0e426766');
        $result = json_decode($json);
        return round($result->main->temp - 273.15, 2);
    }
}

function wp_weather_register_widgets()
{
    register_widget('WpWeatherCurrencyWidget');
}

add_action('widgets_init', 'wp_weather_register_widgets');
