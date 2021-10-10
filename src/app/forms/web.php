<?php
namespace app\forms;

use std, gui, framework, app;


class web extends AbstractForm
{
    /**
     * @event show 
     */
    function doShow(UXWindowEvent $e = null)
    {    
        $this->timer->stop();
    }

    /**
     * @event edit.globalKeyPress-Enter 
     */
    function doEditGlobalKeyPressEnter(UXKeyEvent $e = null)
    {    
        $get_file_inf = new Thread(function () 
        {
            $mediapath = $this->edit->text;
        
            $media_hash = fs::hash($mediapath);
            $media_size = fs::size($mediapath) / 1024 / 1024;
        
            $this->editAlt->text = $media_size + 'MB';
            $this->edit3->text = $media_hash;
        });
        
        $get_file_inf->start();
    }

    /**
     * @event button3.action 
     */
    function doButton3Action(UXEvent $e = null)
    {    
        $this->player->open($this->edit->text);
        $this->player->play();
        $this->form('MainForm')->listView->items->clear();
    }

}
