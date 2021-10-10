//----------------------------------------------
//    .:: This code is a part of Spirit Media Player by Mikhail Samarin ::.
//    .:: Copyright (C) 2021 by Mikhail Samarin ::.
//----------------------------------------------

<?php
namespace app\forms;

use php\io\IOException;
use std, gui, framework, app;
use php\io\Stream;

class settings extends AbstractForm
{
    /**
     * @event panelAlt.click 
     */
    function doPanelAltClick(UXMouseEvent $e = null)
    {    
        $this->panelAlt->borderWidth = 3;
        $this->panel->borderWidth = 1;
        $this->cusH->value = 180;
        $this->cusW->value = 320; //UI/UX
    }

    /**
     * @event panel.click 
     */
    function doPanelClick(UXMouseEvent $e = null)
    {    
        $this->panel->borderWidth = 3;       
        $this->panelAlt->borderWidth = 1;
        $this->cusH->value = 240;
        $this->cusW->value = 320; //UI/UX
    }

    /**
     * @event button6.action 
     */
    function doButton6Action(UXEvent $e = null)
    {    
        app()->hideForm('settings');
    }

    /**
     * @event checkbox.click 
     */
    function doCheckboxClick(UXMouseEvent $e = null)
    {    
        if ($this->checkbox->selected == TRUE)
        {
            $this->cusH->enabled = true;
            $this->cusW->enabled = true;
            $this->panel->borderWidth = 1;
            $this->panelAlt->borderWidth = 1; //UI/UX
        }
        else 
        {
            $this->cusH->enabled = false;
            $this->cusW->enabled = false; //UI/UX
        }
    }

    /**
     * @event checkboxAlt.click 
     */
    function doCheckboxAltClick(UXMouseEvent $e = null)
    {
        if ($this->checkboxAlt->selected == TRUE)
        {
            $this->edit->enabled = true;
            $this->editAlt->enabled = true;
            $this->radioGroup->enabled = true; //UI/UX
        }
        else 
        {
             $this->edit->enabled = false;
             $this->editAlt->enabled = false;
             $this->radioGroup->enabled = false; //UI/UX
        }
    }

    /**
     * @event button7.action 
     */
    function doButton7Action(UXEvent $e = null)
    {
        //---
        //Writing Picture-in-Picture settings
        //---
        $valH = $this->cusH->value;
        $valW = $this->cusW->value;
        $masterdir = $this->getCurrentDir();
        try 
        {
            Stream::putContents("$masterdir\ph.dat", "$valH");
            Stream::putContents("$masterdir\pw.dat", "$valW");
        }
         
        catch (IOException $e) 
        {
            //checking for errors
           alert('Writing error: ' . $e->getMessage());
        }
    }

    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        $this->timer->stop();
    }
}
