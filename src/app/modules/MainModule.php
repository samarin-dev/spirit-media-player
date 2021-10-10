//----------------------------------------------
//    .:: This code is a part of Spirit Media Player by Mikhail Samarin ::.
//    .:: Copyright (C) 2021 by Mikhail Samarin ::.
//----------------------------------------------

<?php
namespace app\modules;

use php\io\IOException;
use php\lang\Thread;
use php\lib\fs;
use php\lib\str;
use php\lang\System;

use std, gui, framework, app;


class MainModule extends AbstractModule
{

    /**
     * @event timer.action 
     */
    function doTimerAction(ScriptEvent $e = null)
    {    
        //Showing playback position on the slider (MainForm) in percents
        //--------------------------------------------------------------
        //Let`s isolate this function from other by creating new Thread
        //This needed, because if the slider on MainForm will be unavilable for any reason, app wil crash
        //(Probably, we can get an other errors, or this problem could be fixed better, but i don`t testing this yet)
        $time_slider = new Thread(function () 
        {
            $this->slider_t->value = $this->player->position;
        });
        
        $time_slider->start();
    }

    /**
     * @event construct 
     */
    function doConstruct(ScriptEvent $e = null)
    {    
        fs::makeDir('./userlib');
    }
    
    function getCurrentDir(){
        //---
        //Fix for read/write logs and configs
        //---
        $path = System::getProperty("java.class.path");
        $pathsep = System::getProperty("path.separator");
        
        if(str::contains($path, $pathsep))
        {
            return dirname(realpath(str::sub($path, 0 , str::pos($path, $pathsep))));
        } 
        else 
        {
            return dirname($path);
        }
    }
    
    function playpause()
    {
        $img_play = new UXImageView(new UXImage('res://.data/img/play.png'));                          
        $play_icon = new UXHBox([$img_play]);
        
        $img_pause = new UXImageView(new UXImage('res://.data/img/pause.png'));                          
        $pause_icon = new UXHBox([$img_pause]);
                
        if ($this->player->status == 'PLAYING') 
        {
            $this->timer->stop();
            $this->player->pause();
            
            $this->form('MainForm')->button->graphic = $play_icon;
            $this->form('MainForm')->imageAlt->show();
        }
        else 
        {
            $this->timer->start();
            $this->player->play();
            
            $this->form('MainForm')->button->graphic = $pause_icon;
            $this->form('MainForm')->imageAlt->hide();
        }
    }
    
    function ScreenSwitch() 
    {
        //---
        //Moving to Full Screen mode
        //---
        $fscreen_img = new UXImageView(new UXImage('res://.data/img/fscreen.png'));                          
        $fscreen_icon = new UXHBox([$fscreen_img]);
        
        $towin_img = new UXImageView(new UXImage('res://.data/img/towin.png'));                          
        $towin_icon = new UXHBox([$towin_img]);
        
        if ($this->fullScreen == TRUE)
        {
            $this->form('MainForm')->fullScreen = false;
            $this->form('MainForm')->panel3->show();
            $this->form('MainForm')->panel4->show();
            $this->form('MainForm')->button8->graphic = $fscreen_icon;
            $this->image4->hide();
        }
        else 
        {
            $this->form('MainForm')->panel3->hide();
            $this->form('MainForm')->panel4->hide();
            $this->form('MainForm')->button8->graphic = $towin_icon;
            $this->form('MainForm')->fullScreen = true;
            $this->toast('Press F2 to hide media control panel and queue panel');
            $this->image4->show();
        }
    }
    
    function pinp_switch()
    {
        //---
        //Moving to Picture-in-Picture mode
        //---
        try 
        {
            $masterdir = $this->getCurrentDir();

            $size_w = file_get_contents("$masterdir\pw.dat");
            $size_h = file_get_contents("$masterdir\ph.dat");
            
            $this->form('MainForm')->size = [320, 240];
            
            $this->form('MainForm')->panel3->hide();
            $this->form('MainForm')->panel->hide();
            $this->form('MainForm')->panelAlt->hide();
            $this->form('MainForm')->panel4->hide();
        }
         
        catch (IOException $e) 
        {
            //checking for errors
           alert('Reading error: ' . $e->getMessage());
        }
    }
    
    function normalize()
    {
        //---
        //Moving to normal mode
        //---
        $this->form('MainForm')->panel3->show();
        $this->form('MainForm')->panel->show();
        $this->form('MainForm')->panelAlt->show();
        $this->form('MainForm')->panel4->show();
        $this->form('MainForm')->width = 640;
        $this->form('MainForm')->height = 592;
        $this->button14->text = '<>';
        
        $this->form('MainForm')->fullScreen = false;
        
        $fscreen_img = new UXImageView(new UXImage('res://.data/img/fscreen.png'));                          
        $fscreen_icon = new UXHBox([$fscreen_img]);
        
        $this->form('MainForm')->button8->graphic = $fscreen_icon;
        $this->image4->hide();
    }
    
    function UXMaximize()
    {
        $this->form('MainForm')->maximize();
        $this->button14->text = '><';
    }
    
    function mmswitch()
    {
        //---
        //Switching between normal and maximized mode
        //---
        if ($this->width > 640)
        {
            $this->normalize();
        }
        else 
        {
            $this->UXMaximize();
        }
    }

}
