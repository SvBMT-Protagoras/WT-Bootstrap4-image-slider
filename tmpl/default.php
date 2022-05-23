<?php
/*
 * @version        mod_wt_boostrap4_slider.php 1.0.2
 * @package        Bootstrap 4 image slider for Joomla
 * @copyright   Copyright (C) 2020 Sergey Tolkachyov
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/
defined('_JEXEC') or die('Restricted access');

if($use_individual_time_interval == 0 and $time_interval != ""){
    $time_interval = 'interval: '.$time_interval;
} else {
    $time_interval = "";
}
$script ='
jQuery(document).ready(function() {
    jQuery("#wt_bs4_image_slider-'.$moduleId.'").carousel({'.$time_interval.'});
});
';

$doc->addScriptDeclaration($script);

if($params->get("show_many_move_one") == 1) {
    $script2 = '
    jQuery(".carousel-item").each(function(){
        var itemToClone = $(this);

        for (var i=1;i<4;i++) {
          itemToClone = itemToClone.next();

          // wrap around if at end of item collection
          if (!itemToClone.length) {
            itemToClone = $(this).siblings(":first");
          }

          // grab item, clone, add marker class, add to collection
          itemToClone.children(":first-child").clone()
            .addClass("cloneditem-"+(i))
            .appendTo($(this));
        }
    });
    ';

    $doc->addScriptDeclaration($script2);
}

function showIndicators($params, $moduleId) {
    echo <<<HTML
    <ol class="carousel-indicators">
    HTML;
    for($i=0;$i<count((array)$params->get("fields"));$i++){
        if($i + 1 == 1) {
            echo <<<HTML
            <li data-target="#wt_bs4_image_slider-{$moduleId}" data-slide-to="{$i}" class=active></li>
            HTML;
        } else {
            echo <<<HTML
            <li data-target="#wt_bs4_image_slider-{$moduleId}" data-slide-to="{$i}"></li>
            HTML;
        }
    }
    echo "</ol>";
}

function showControls($moduleId) {
    echo <<<HTML
    <a class="carousel-control-prev" href="#wt_bs4_image_slider-{$moduleId}" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#wt_bs4_image_slider-{$moduleId}" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
    HTML;
}

function titleCaptionButtons($field) {
    if (!empty($field->title) || !empty($field->subtitle) || $field->cta_btn == 1) { 
        echo <<<HTML
        <div class="carousel-caption d-none d-md-block">
        HTML;
        if (!empty($field->title)) {
            echo <<<HTML
            <h5 class="carousel-caption-title">{$field->title}</h5>
            HTML;
        }
        if (!empty($field->subtitle)) {
            echo <<<HTML
            <p class="carousel-caption-subtitle">{$field->subtitle}</p>
            HTML;
        }                    
        if (!empty($field->cta_btn) == 1) {
            if(!empty($field->cta_btn_goals)) {
                $onClick = "onclick={$field->cta_btn_goals}";
            }

            echo <<<HTML
            <a 
                class="carousel-caption-btn {$field->cta_btn_css}"
                href="{$field->cta_btn_url}"
                {$onClick}
            >
            {$field->cta_btn_text}
            </a>
            HTML;
        }
        echo "</div>";
    }
}

function showCarousel($params, $field, $moduleId) {
    $k=0;
    foreach($params->get("fields") as $field) {
        if($k+1 == 1) {
            $activeStatus = "active";
        } else {
            $activeStatus = "";
        }

        if($params->get("use_individual_time_interval") == 1) {
            $interval = $field->individual_time_interval*1000;
            echo <<<HTML
            <div class="carousel-item {$activeStatus}" data-interval="{$interval}">
            <img src="{$field->image}" class="d-block w-100" alt="{$field->title}">
            HTML; 
        } else {
            echo <<<HTML
            <div class="carousel-item {$activeStatus}">
            <img src="{$field->image}" class="d-block w-100" alt="{$field->title}">
            HTML;
        }   

        titleCaptionButtons($field);
        
        $k++;
        echo "</div>";
    }
    if ($use_controls == 1) {
        showControls($moduleId);
    }
    echo "</div></div>";
}

function showManyMoveOneCarousel($params, $field, $moduleId) {
    $k=0;
    foreach($params->get("fields") as $field) {
        if($k+1 == 1) {
            $activeStatus = "active";
        } else {
            $activeStatus = "";
        }

        echo <<<HTML
        <div class="carousel-item {$activeStatus}">
        <img src="{$field->image}" class="d-block w-100" alt="{$field->title}">
        </div>
        HTML;

        $k++;
    }
    if ($use_controls == 1) {
        showControls($moduleId);
    }
    echo "</div></div>";
}

if($params->get("crossfade") == 1) {
    $fade = "carousel-fade";
} else {
    $fade = "";
}

echo <<<HTML
<div id="wt_bs4_image_slider-{$moduleId}" class="carousel slide {$fade} {$moduleclass_sfx}" data-ride="carousel">
HTML;

if ($params->get("use_indicators") == 1) {
    showIndicators($params, $moduleId);
}
   
echo <<<HTML
<div class="carousel-inner">
HTML;

if($params->get("show_many_move_one") == 1) {
    showManyMoveOneCarousel($params, $field, $moduleId);
} else{
    showCarousel($params, $field, $moduleId);
}

?>
