<?php

namespace Spool\Zookeeper\Lib;

use Spool\Zookeeper\System\Timeval;

class Log
{
    const TIME_NOW_BUF_SIZE = 1024;
    const FORMAT_LOG_BUF_SIZE = 4096;
    const ZOO_LOG_LEVEL_ERROR = 1;
    const ZOO_LOG_LEVEL_WARN = 2;
    const ZOO_LOG_LEVEL_INFO = 3;
    const ZOO_LOG_LEVEL_DEBUG = 4;
    public static $logStream = null;
    public static $logLevel = self::ZOO_LOG_LEVEL_INFO;
    
    public static function LOG_ERROR(string $msg, int $line, string $functionName){
        
        if(self::$logLevel >= self::ZOO_LOG_LEVEL_ERROR){
            self::log_message(self::ZOO_LOG_LEVEL_ERROR, $line, $functionName, $msg);
        }
    }
    public static function LOG_WARN(string $msg, int $line, string $functionName){
        
        if(self::$logLevel >= self::ZOO_LOG_LEVEL_WARN){
            self::log_message(self::ZOO_LOG_LEVEL_WARN, $line, $functionName, $msg);
        }
    }
    public static function LOG_INFO(string $msg, int $line, string $functionName){
        
        if(self::$logLevel >= self::ZOO_LOG_LEVEL_INFO){
            self::log_message(self::ZOO_LOG_LEVEL_INFO, $line, $functionName, $msg);
        }
    }
    public static function LOG_DEBUG(string $msg, int $line, string $functionName){
        
        if(self::$logLevel >= self::ZOO_LOG_LEVEL_DEBUG){
            self::log_message(self::ZOO_LOG_LEVEL_DEBUG, $line, $functionName, $msg);
        }
    }
    public static function freeBuffer($p){
        if($p) $p = null;
    }
    public static function prepareTSDKeys() {
    //    pthread_key_create (&time_now_buffer, freeBuffer);
    //    pthread_key_create (&format_log_msg_buffer, freeBuffer);
    }
    public static function getTSData(pthread_key_t $key,int $size) : string
    {
        $p = ''; return $p;
        $p = pthread_getspecific($key);
        if($p==0){
            $res = 0;
            $p=calloc(1,size);
            $res=pthread_setspecific(key,p);
            if($res!=0){
                fprintf(stderr,"Failed to set TSD key: %d",res);
            }
        }
        return p;
    }
    /*
    public static function get_time_buffer() : string
    {
    //    return getTSData(time_now_buffer,TIME_NOW_BUF_SIZE);
    }
    //*/
    public static function get_time_buffer() : string
    {
    //    static char buf[TIME_NOW_BUF_SIZE];
        $buf = '';
        return $buf;    
    }
    public static function get_format_log_buffer() : string
    {
    //    static char buf[FORMAT_LOG_BUF_SIZE];
        $buf = '';
        return $buf;
    }
    public static function getLogStream()
    {
        
        if(!self::$logStream)
            self::$logStream = 'php://stderr';
            
        return fopen(self::$logStream, 'a');
    }
    public static function zoo_set_log_stream(&$stream){
        
        if(is_resource($stream) && get_resource_type($stream) == 'stream'){
            self::$logStream = $stream;
        }
    }
    public static function time_now() : string
    {
        /*
        struct timeval tv;
        struct tm lt;
        time_t now = 0;
        size_t len = 0;
        */
        $tv = new Timeval();
        /*
        $lt = null;
        $now = 0;
        $len = 0
        
        gettimeofday(&tv,0);

        now = tv.tv_sec;
        localtime_r(&now, &lt);

        // clone the format used by log4j ISO8601DateFormat
        // specifically: "yyyy-MM-dd HH:mm:ss,SSS"

        len = strftime(now_str, TIME_NOW_BUF_SIZE,
                              "%Y-%m-%d %H:%M:%S",
                              &lt);

        len += snprintf(now_str + len,
                        TIME_NOW_BUF_SIZE - len,
                        ",%03d",
                        (int)(tv.tv_usec/1000));
        //*/
        $now_str = date("Y-m-d H:i:s", $tv->tv_sec);
        $now_str .= ",";
        $now_str .= $tv->tv_usec/1000;
        return $now_str;
    }
    public static function log_message(int $curLevel, int $line, string &$fileName, string &$message)
    {
        $dbgLevelStr=["ZOO_INVALID","ZOO_ERROR","ZOO_WARN","ZOO_INFO","ZOO_DEBUG"];
        $pid=0;
        $timebuf = '';
        if($pid==0) $pid = getmypid();
        $time = self::time_now(self::get_time_buffer());
        $levelstr = $dbgLevelStr[$curLevel];
        $msg = "$time:$pid:$levelstr@$fileName@$line: $message\n";
        /*
        if(defined('THREADED')){
            // pid_t is long on Solaris
            fprintf(LOGSTREAM, "%s:%ld:%s@%s@%d: %s\n", time_now(get_time_buffer()),(long)pid,
                    dbgLevelStr[curLevel],funcName,line,message);
        }else{
            if(defined('WIN32')){
                fprintf(LOGSTREAM, "%s:%d(0x%lx):%s@%s@%d: %s\n", time_now(timebuf),pid,
                        (unsigned long int)(pthread_self().thread_id),
                        dbgLevelStr[curLevel],funcName,line,message);      
            }else{
                fprintf(LOGSTREAM, "%s:%ld(0x%lx):%s@%s@%d: %s\n", time_now(get_time_buffer()),(long)pid,
                        (unsigned long int)pthread_self(),
                        dbgLevelStr[curLevel],funcName,line,message);      
            }
        }
        //*/
        fwrite(LOGSTREAM, $msg, strlen($msg));
        fflush(LOGSTREAM);
    }
    public static function format_log_message(&$format, ...$args) : string
    {
    //    va_list va;
        $buf = get_format_log_buffer();
        $buf .= $format;
        if(func_num_args()){
            foreach($args as $value){
                $buf .= $value;
            }
        }
    //    va_start(va,format);
    //    vsnprintf(buf, FORMAT_LOG_BUF_SIZE-1,format,va);
    //    va_end(va); 
        return $buf;
    }
    public static function zoo_set_debug_level(int $level)
    {
        
        if($level==0){
            // disable logging (unit tests do this)
            self::$logLevel = 0;
            return;
        }
        if($level<self::ZOO_LOG_LEVEL_ERROR) $level = self::ZOO_LOG_LEVEL_ERROR;
        if($level>self::ZOO_LOG_LEVEL_DEBUG) $level = self::ZOO_LOG_LEVEL_DEBUG;
        self::$logLevel=$level;
    }
}
