<?php

use Spool\Zookeeper\Lib\Log;

define('BIG_ENDIAN', pack('L', 1) === pack('N', 1));
defined('LOGFILE') || define('LOGFILE', 'php://stderr');
define('LOGSTREAM', Log::getLogStream());
//define('EXPIRED_SESSION_STATE_DEF', -112);
//define('AUTH_FAILED_STATE_DEF', -113);
//define('CONNECTING_STATE_DEF', 1);
//define('ASSOCIATING_STATE_DEF', 2);
//define('CONNECTED_STATE_DEF', 3);
//define('NOTCONNECTED_STATE_DEF', 999);
//define('ZOO_EPHEMERAL', 1);
//define('ZOO_SEQUENCE', 2);
//define('Spool\Zookeeper\', TRUE);

define('PTHREADS', FALSE);

define('IPPROTO_TCP', 6);       /* Transmission Control Protocol.  */

define('EAI_BADFLAGS', -1);    /* Invalid value for `ai_flags' field.  */
define('EAI_NONAME', -2);    /* NAME or SERVICE is unknown.  */
define('EAI_AGAIN', -3);    /* Temporary failure in name resolution.  */
define('EAI_FAIL', -4);    /* Non-recoverable failure in name res.  */
define('EAI_FAMILY', -6);    /* `ai_family' not supported.  */
define('EAI_SOCKTYPE', -7);    /* `ai_socktype' not supported.  */
define('EAI_SERVICE', -8);    /* SERVICE not supported for `ai_socktype'.  */
define('EAI_MEMORY', -10);    /* Memory allocation failure.  */
define('EAI_SYSTEM', -11);    /* System error returned in `errno'.  */
define('EAI_OVERFLOW', -12);    /* Argument buffer overflow.  */
define('EAI_NODATA', -5);    /* No address associated with NAME.  */
define('EAI_ADDRFAMILY', -9);    /* Address family for NAME not supported.  */
define('EAI_INPROGRESS', -100);    /* Processing request in progress.  */
define('EAI_CANCELED', -101);    /* Request canceled.  */
define('EAI_NOTCANCELED', -102);    /* Request not canceled.  */
define('EAI_ALLDONE', -103);    /* All requests done.  */
define('EAI_INTR', -104);    /* Interrupted by a signal.  */
define('EAI_IDN_ENCODE', -105);    /* IDN encoding failed.  */

define('ENOENT', 2);       //无此文件或目录
define('E2BIG', 7);        //定义一个标准错误：linux错误代码 参数列表过长
define('EINVAL', 22);      //定义一个标准错误：linux错误代码 无效的参数
define('ENOMEM', 12);      //定义一个标准错误：linux错误代码 内存溢出
define('ESTALE', 116);    //定义一个标准错误：linux错误代码 Stale NFS file handle
define('INT32_T', 4);      //定义int32的字符大小
define('INT64_T', 8);      //定义int64的字符大小
define('BOOL', 1);         //定义BOOL的字符大小

define('PF_UNSPEC', 0);
define('PF_INET', 2);
define('PF_INET6', 10);

define('AF_UNSPEC', PF_UNSPEC);
//define('AF_INET', PF_INET);
//define('AF_INET6', PF_INET6);

define('ZOO_MAJOR_VERSION', 3);
define('ZOO_MINOR_VERSION', 4);
define('ZOO_PATCH_VERSION', 11);

define('ZOOKEEPER_WRITE', 1);
define('ZOOKEEPER_READ', 2);

define('ZOO_EPHEMERAL', 1);
define('ZOO_SEQUENCE', 2);

define('ZOK', 0); /*!< Everything is OK */
/** System and server-side errors.
 * This is never thrown by the server; it shouldnt be used other than
 * to indicate a range. Specifically error codes greater than this
 * value; but lesser than{@link #ZAPIERROR}; are system errors.
 */
define('ZSYSTEMERROR', -1);
define('ZRUNTIMEINCONSISTENCY', -2); /*!< A runtime inconsistency was found */
define('ZDATAINCONSISTENCY', -3); /*!< A data inconsistency was found */
define('ZCONNECTIONLOSS', -4); /*!< Connection to the server has been lost */
define('ZMARSHALLINGERROR', -5); /*!< Error while marshalling or unmarshalling data */
define('ZUNIMPLEMENTED', -6); /*!< Operation is unimplemented */
define('ZOPERATIONTIMEOUT', -7); /*!< Operation timeout */
define('ZBADARGUMENTS', -8); /*!< Invalid arguments */
define('ZINVALIDSTATE', -9);
/** API errors.
 * This is never thrown by the server; it shouldnt be used other than
 * to indicate a range. Specifically error codes greater than this
 * value are API errors (while values less than this indicate a
 *{@link #ZSYSTEMERROR}).
 */
define('ZAPIERROR', -100);
define('ZNONODE', -101); /*!< Node does not exist */
define('ZNOAUTH', -102); /*!< Not authenticated */
define('ZBADVERSION', -103); /*!< Version conflict */
define('ZNOCHILDRENFOREPHEMERALS', -108); /*!< Ephemeral nodes may not have children */
define('ZNODEEXISTS', -110); /*!< The node already exists */
define('ZNOTEMPTY', -111); /*!< The node has children */
define('ZSESSIONEXPIRED', -112); /*!< The session has been expired by the server */
define('ZINVALIDCALLBACK', -113); /*!< Invalid callback specified */
define('ZINVALIDACL', -114); /*!< Invalid ACL specified */
define('ZAUTHFAILED', -115); /*!< Client authentication failed */
define('ZCLOSING', -116); /*!< ZooKeeper is closing */
define('ZNOTHING', -117); /*!< (not error) no server responses to process */
define('ZSESSIONMOVED', -118); /*!<session moved to another server; so operation is ignored */

define('ZOO_LOG_LEVEL_ERROR', 1);
define('ZOO_LOG_LEVEL_WARN', 2);
define('ZOO_LOG_LEVEL_INFO', 3);
define('ZOO_LOG_LEVEL_DEBUG', 4);

define('ZOO_PERM_READ', 1);
define('ZOO_PERM_WRITE', 2);
define('ZOO_PERM_CREATE', 4);
define('ZOO_PERM_DELETE', 8);
define('ZOO_PERM_ADMIN', 16);
define('ZOO_PERM_ALL', 31);

define('ZOO_NOTIFY_OP', 0);
define('ZOO_CREATE_OP', 1);
define('ZOO_DELETE_OP', 2);
define('ZOO_EXISTS_OP', 3);
define('ZOO_GETDATA_OP', 4);
define('ZOO_SETDATA_OP', 5);
define('ZOO_GETACL_OP', 6);
define('ZOO_SETACL_OP', 7);
define('ZOO_GETCHILDREN_OP', 8);
define('ZOO_SYNC_OP', 9);
define('ZOO_PING_OP', 11);
define('ZOO_GETCHILDREN2_OP', 12);
define('ZOO_CHECK_OP', 13);
define('ZOO_MULTI_OP', 14);
define('ZOO_CLOSE_OP', -11);
define('ZOO_SETAUTH_OP', 100);
define('ZOO_SETWATCHES_OP', 101);

define('WATCHER_EVENT_XID', -1);
define('PING_XID', -2);
define('AUTH_XID', -4);
define('SET_WATCHES_XID', -8);

define('EXPIRED_SESSION_STATE_DEF', -112);
define('AUTH_FAILED_STATE_DEF', -113);
define('CONNECTING_STATE_DEF', 1);
define('ASSOCIATING_STATE_DEF', 2);
define('CONNECTED_STATE_DEF', 3);
define('NOTCONNECTED_STATE_DEF', 999);

define('CREATED_EVENT_DEF', 1);
define('DELETED_EVENT_DEF', 2);
define('CHANGED_EVENT_DEF', 3);
define('CHILD_EVENT_DEF', 4);
define('SESSION_EVENT_DEF', -1);
define('NOTWATCHING_EVENT_DEF', -2);

define('ZOO_EXPIRED_SESSION_STATE', EXPIRED_SESSION_STATE_DEF);
define('ZOO_AUTH_FAILED_STATE', AUTH_FAILED_STATE_DEF);
define('ZOO_CONNECTING_STATE', CONNECTING_STATE_DEF);
define('ZOO_ASSOCIATING_STATE', ASSOCIATING_STATE_DEF);
define('ZOO_CONNECTED_STATE', CONNECTED_STATE_DEF);

define('ZOO_CREATED_EVENT', CREATED_EVENT_DEF);
define('ZOO_DELETED_EVENT', DELETED_EVENT_DEF);
define('ZOO_CHANGED_EVENT', CHANGED_EVENT_DEF);
define('ZOO_CHILD_EVENT', CHILD_EVENT_DEF);
define('ZOO_SESSION_EVENT', SESSION_EVENT_DEF);
define('ZOO_NOTWATCHING_EVENT', NOTWATCHING_EVENT_DEF);

define('COMPLETION_WATCH', -1);
define('COMPLETION_VOID', 0);
define('COMPLETION_STAT', 1);
define('COMPLETION_DATA', 2);
define('COMPLETION_STRINGLIST', 3);
define('COMPLETION_STRINGLIST_STAT', 4);
define('COMPLETION_ACLLIST', 5);
define('COMPLETION_STRING', 6);
define('COMPLETION_MULTI', 7);

define('ENOTSOCK',      88);    /* Socket operation on non-socket */ 
define('EDESTADDRREQ',  89);    /* Destination address required */ 
define('EMSGSIZE',      90);    /* Message too long */ 
define('EPROTOTYPE',    91);    /* Protocol wrong type for socket */ 
define('ENOPROTOOPT',   92);    /* Protocol not available */ 
define('EPROTONOSUPPORT', 93);  /* Protocol not supported */ 
define('ESOCKTNOSUPPORT', 94);  /* Socket type not supported */ 
define('EOPNOTSUPP',    95);    /* Operation not supported on transport endpoint */ 
define('EPFNOSUPPORT',  96);    /* Protocol family not supported */ 
define('EAFNOSUPPORT',  97);    /* Address family not supported by protocol */ 
define('EADDRINUSE',    98);    /* Address already in use */ 
define('EADDRNOTAVAIL', 99);    /* Cannot assign requested address */ 
define('ENETDOWN',      100);   /* Network is down */ 
define('ENETUNREACH',   101);   /* Network is unreachable */ 
define('ENETRESET',     102);   /* Network dropped connection because of reset */ 
define('ECONNABORTED',  103);   /* Software caused connection abort */ 
define('ECONNRESET',    104);   /* Connection reset by peer */ 
define('ENOBUFS',       105);   /* No buffer space available */ 
define('EISCONN',       106);   /* Transport endpoint is already connected */ 
define('ENOTCONN',      107);   /* Transport endpoint is not connected */ 
define('ESHUTDOWN',     108);   /* Cannot send after transport endpoint shutdown */ 
define('ETOOMANYREFS',  109);   /* Too many references: cannot splice */ 
define('ETIMEDOUT',     110);   /* Connection timed out */ 
define('ECONNREFUSED',  111);   /* Connection refused */ 
define('EHOSTDOWN',     112);   /* Host is down */ 
define('EHOSTUNREACH',  113);   /* No route to host */ 
define('EALREADY',      114);   /* Operation already in progress */ 
define('EINPROGRESS',   115);   /* Operation now in progress */ 
define('EREMOTEIO',     121);   /* Remote I/O error */ 
define('ECANCELED',     125);   /* Operation Canceled */ 
define('SYNCHRONOUS_MARKER', null); /*尝试将同步标志设置为null*/

define('WSAEWOULDBLOCK', ENOBUFS);
define('WSAEINPROGRESS', 10036);
define('EWOULDBLOCK',    WSAEWOULDBLOCK);
//define('EINPROGRESS', WSAEINPROGRESS);

define('POLLIN',   0x0001);
define('POLLPRI',  0x0002);
define('POLLOUT',  0x0004);
define('POLLERR',  0x0008);
define('POLLHUP',  0x0010);
define('POLLNVAL', 0x0020);

define('POLLRDNORM', 0x0040);
define('POLLRDBAND', 0x0080);
define('POLLWRNORM', 0x0100);
define('POLLWRBAND', 0x0200);
define('POLLMSG',    0x0400);
define('POLLREMOVE', 0x1000);
define('POLLRDHUP',  0x2000);
