<?php

namespace Spool\Zookeeper\Hashtable;

use Spool\Zookeeper\Adaptor\ZhandleT;
use Spool\Zookeeper\Hashtable\WatcherObjectList;
use Spool\Zookeeper\Hashtable\WatcherObjectT;

class Hashtable
{
        public static function collectWatchers(ZhandleT &$zh, int $type, string $path) : WatcherObjectList
    {
        $list = new WatcherObjectList();

        if($type == ZOO_SESSION_EVENT){
            $defWatcher = new WatcherObjectT();
            $defWatcher->watcher = $zh->watcher;
            $defWatcher->context = $zh->context;
            self::add_to_list($list, $defWatcher, 1);
            self::collect_session_watchers($zh, $list);
            return $list;
        }
        switch($type){
        case CREATED_EVENT_DEF:
        case CHANGED_EVENT_DEF:
            // look up the watchers for the path and move them to a delivery list
            self::add_for_event($zh->active_node_watchers, $path, $list);
            self::add_for_event($zh->active_exist_watchers, $path, $list);
            break;
        case CHILD_EVENT_DEF:
            // look up the watchers for the path and move them to a delivery list
            self::add_for_event($zh->active_child_watchers, $path, $list);
            break;
        case DELETED_EVENT_DEF:
            // look up the watchers for the path and move them to a delivery list
            self::add_for_event($zh->active_node_watchers, $path, $list);
            self::add_for_event($zh->active_exist_watchers, $path, $list);
            self::add_for_event($zh->active_child_watchers, $path, $list);
            break;
        }
        return $list;
    }
    
    public static function add_for_event(array &$ht, string $path, WatcherObjectList &$list)
    {
        if(isset($ht[$path])){
            $wl = $ht[$path];
            self::copy_watchers($wl, $list, 0);
            unset($ht[$path]);
        }
    }
    
    public static function copy_watchers(WatcherObjectList &$from, WatcherObjectList &$to, int $clone)
    {
        $wo = &$from->head;
        while($wo){
            $next = &$wo->next;
            self::add_to_list($to, $wo, $clone);
            $wo = $next; 
        }
    }
    
    public static function copy_table(array &$from, watcher_object_list_t &$to)
    {
        if(count($from)){
            return;
        }
        foreach($from as $w){
            self::copy_watchers($w, $to, 1);
        }
    }
    
    public static function collect_session_watchers(ZhandleT &$zh, WatcherObjectList &$list)
    {
        self::copy_table($zh->active_node_watchers, $list);
        self::copy_table($zh->active_exist_watchers, $list);
        self::copy_table($zh->active_child_watchers, $list);
    }
    
    public static function add_to_list(WatcherObjectList &$wl, WatcherObjectT &$wo, int $clone) : bool
    {
        if(!self::search_watcher($wl, $wo)){
            if($clone){
                $cloned = clone $wo;
            }else{
                $cloned = $wo;
            }
            array_unshift($wl->head, $cloned);
        }else{
            $wo = null;
        }
    }
    
    public static function search_watcher(WatcherObjectList $wl, WatcherObjectT $wo)
    {
        if(in_array($wo, $wl->head)){
            return $wo;
        }
        return null;
    }
}
