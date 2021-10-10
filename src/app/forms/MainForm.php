//----------------------------------------------
//    .:: This code is a part of Spirit Media Player by Mikhail Samarin ::.
//    .:: Copyright (C) 2021 by Mikhail Samarin ::.
//----------------------------------------------

<?php
namespace app\forms;

use php\io\IOException;
use std, gui, framework, app;

use php\gui\framework\AbstractForm;
use php\gui\event\UXEvent; 
use php\gui\event\UXWindowEvent; 
use php\io\File;
use php\io\Stream;
use php\gui\UXFileChooser;
use php\gui\UXImage;
use script\MediaPlayerScript;

class MainForm extends AbstractForm
{

    /**
     * @event listView.action 
     */
    function doListViewAction(UXEvent $e = null)
    {    
        global $files;
           
         $this->player->open($files[$this->listView->focusedIndex]);
         $this->playpause();
    }


    /**
     * @event slider_t.click 
     */
    function doSlider_tClick(UXMouseEvent $e = null)
    {    
        $this->player->position = $this->slider_t->value;
    }

    /**
     * @event slider_t.mouseEnter 
     */
    function doSlider_tMouseEnter(UXMouseEvent $e = null)
    {    
        $this->timer->stop();
    }

    /**
     * @event slider_t.mouseExit 
     */
    function doSlider_tMouseExit(UXMouseEvent $e = null)
    {    
        $this->timer->start();
    }

    /**
     * @event sliderAlt.mouseDrag 
     */
    function doSliderAltMouseDrag(UXMouseEvent $e = null)
    {    
        $this->player->volume = $this->sliderAlt->value;
    }

    /**
     * @event buttonAlt.action 
     */
    function doButtonAltAction(UXEvent $e = null)
    {    
        global $files;
    
        $this->player->stop();
        $this->timer->stop();
            
        $this->player->open($files[$this->listView->focusedIndex + 1]);
        $this->listView->focusedIndex = $this->listView->focusedIndex + 1;
        
        $this->player->play();
        $this->timer->start();
    }

    /**
     * @event button3.action 
     */
    function doButton3Action(UXEvent $e = null)
    {
        global $files;
        
        if ($this->slider_t->value > 25)
        {
            $this->player->position = 0;
        }
        else 
        {
            $this->player->open($files[$this->listView->focusedIndex - 1]);
            $this->listView->focusedIndex = $this->listView->focusedIndex - 1;
        
            $this->player->play();
            $this->timer->start();
        }
    }

    /**
     * @event button4.action 
     */
    function doButton4Action(UXEvent $e = null)
    {    
        $cur_pos = $this->player->positionMs;
        $this->player->positionMs = $cur_pos + 5000;
    }

    /**
     * @event button5.action 
     */
    function doButton5Action(UXEvent $e = null)
    {    
        $cur_pos = $this->player->positionMs;
        $this->player->positionMs = $cur_pos - 5000;
    }

    /**
     * @event button12.action 
     */
    function doButton12Action(UXEvent $e = null)
    {    
        $this->listView->items->clear();
    }


    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        $this->FileChooser = new UXFileChooser();
        $this->FileChooser->title = 'Choose file(s)';
        $this->FileChooser->padding = [1, 1, 1, 1];
        
        $this->FileChooser->extensionFilters = [
            ['description' => 'All supported types', 'extensions' => ['*.mp3', '*.bit', '*.wav', '*.wave', '*.aif', '*.aiff', '*.aifc', '*.flv', '*.f4v', '*.f4p', '*.f4a', '*.f4b', '*.mp4', '*.m4a', '*.m4p', '*.m4b', '*.m4r', '*.m4v']],
            ['description' => 'MP3 audio', 'extensions' => ['*.mp3', '*.bit']],
            ['description' => 'WAV audio', 'extensions' => ['*.wav', '*.wave']],
            ['description' => 'AIF audio', 'extensions' => ['*.aif', '*.aiff', '*.aifc']],
            ['description' => 'FLV video', 'extensions' => ['*.flv', '*.f4v', '*.f4p', '*.f4a', '*.f4b']],
            ['description' => 'MP4 video', 'extensions' => ['*.mp4', '*.m4a', '*.m4p', '*.m4b', '*.m4r', '*.m4v']],
            ['description' => 'Any files', 'extensions' => ['*.*']]
        ];
    }


    /**
     * @event button8.action 
     */
    function doButton8Action(UXEvent $e = null)
    {    
        $this->ScreenSwitch();
    }


    /**
     * @event imageAlt.click 
     */
    function doImageAltClick(UXMouseEvent $e = null)
    {    
        $this->playpause();
    }

    /**
     * @event mediaView.click 
     */
    function doMediaViewClick(UXMouseEvent $e = null)
    {
        $this->playpause();
    }

    /**
     * @event button.action 
     */
    function doButtonAction(UXEvent $e = null)
    {
        $this->playpause();
    }



    /**
     * @event mediaView.click-2x 
     */
    function doMediaViewClick2x(UXMouseEvent $e = null)
    {    
        $this->normalize();
    }

    /**
     * @event imageAlt.click-2x 
     */
    function doImageAltClick2x(UXMouseEvent $e = null)
    {    
        $this->normalize();
    }

    /**
     * @event button13.action 
     */
    function doButton13Action(UXEvent $e = null)
    {    
        app()->shutdown();
    }

    /**
     * @event button15.action 
     */
    function doButton15Action(UXEvent $e = null)
    {    
        app()->minimizeForm('MainForm');
    }

    /**
     * @event button14.action 
     */
    function doButton14Action(UXEvent $e = null)
    {    
        $this->mmswitch();
    }

    /**
     * @event button9.action 
     */
    function doButton9Action(UXEvent $e = null)
    {    
        $this->pinp_switch();
    }

    /**
     * @event panel4.click 
     */
    function doPanel4Click(UXMouseEvent $e = null)
    {    
        $this->playpause();
    }

    /**
     * @event panel4.click-2x 
     */
    function doPanel4Click2x(UXMouseEvent $e = null)
    {    
        $this->normalize();
    }

    /**
     * @event keyDown-Space 
     */
    function doKeyDownSpace(UXKeyEvent $e = null)
    {    
        $this->playpause();
    }



    /**
     * @event button3.click-2x 
     */
    function doButton3Click2x(UXMouseEvent $e = null)
    {    
            global $files;

            $this->player->open($files[$this->listView->focusedIndex - 1]);
            $this->listView->focusedIndex = $this->listView->focusedIndex - 1;
        
            $this->player->play();
            $this->timer->start();
    }

    /**
     * @event panel3.click-2x 
     */
    function doPanel3Click2x(UXMouseEvent $e = null)
    {    
        $this->maximize();
    }

    /**
     * @event label.click-2x 
     */
    function doLabelClick2x(UXMouseEvent $e = null)
    {    
        $this->maximize();
    }

    /**
     * @event button6.action 
     */
    function doButton6Action(UXEvent $e = null)
    {
        global $files;
        $this->listView->items->clear();
        
        if ($files = $this->FileChooser->showOpenMultipleDialog()) {
            foreach ($files as $file) $this->listView->items->add($file->getName());} 
            
        $this->player->open($files[$this->listView->focusedIndex]);
        $this->player->play();
    }

    /**
     * @event button16.action 
     */
    function doButton16Action(UXEvent $e = null)
    {    
        app()->showForm('settings');
    }

    /**
     * @event button17.action 
     */
    function doButton17Action(UXEvent $e = null)
    {
        app()->showForm('settings');
        $this->form('settings')->tabPane->selectLastTab();
    }

    /**
     * @event button7.action 
     */
    function doButton7Action(UXEvent $e = null)
    {    
        app()->showForm('web');
    }

    /**
     * @event keyDown-F11 
     */
    function doKeyDownF11(UXKeyEvent $e = null)
    {    
        $this->ScreenSwitch();
    }

    /**
     * @event keyDown-F2 
     */
    function doKeyDownF2(UXKeyEvent $e = null)
    {    
        $this->panel->opacity = 0;
        $this->panelAlt->opacity = 0;
    }

    /**
     * @event image4.click 
     */
    function doImage4Click(UXMouseEvent $e = null)
    {
        $this->ScreenSwitch();
    }

}
