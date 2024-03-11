<!-- START_METADATA
---
title: Enhanced logging
sidebar_label: Enhanced logging
sidebar_position: 10
pagination_next: null
pagination_prev: null
---
END_METADATA -->


# Enhanced Logging
- [Enable Enhanced Logging](#enable-enhanced-logging)
- [Logging level table](#logging-level-table)

### Enable Enhanced Logging
Go to my extensions, and then click the 3 dots for the plugin
![configure plugin](./images/installation/plugin_configure.png)

Then click configure. Select the Sales Channel where you want enhanced logging <br>
scroll down to *Vipps MobilePay Settings* where you will see *Enable enhanved logging*
![enable enhanced logging](./images/enhanced_logging/plugin_enhanced_logging.png)

Click *Enable enhanced logging* and clear the cache

It's now possible to see all the logs `https://<domain>/admin#/sw/settings/logging/list` or Settings -> System -> Event logs 

A log could look like this
![exampel log](./images/enhanced_logging/plugin_log_example.png)

This is the content thats get set in the logging
```PHP
'message' => $event,
'context' => $context,
'level' => $level,
'channel' => self::LOG_CHANNEL
```

### Logging level table
|    Level |                                                                     Message | Code |  Shows by default  |  Shows with enhanced logging  |
|---------:|----------------------------------------------------------------------------:|-----:|:------------------:|:-----------------------------:|
|    Debug |                                                  Detailed debug information |  100 |         ❌          |               ✅               |
|  Warning | Exceptional occurrences that are not errors.<br> e.g use of deprecated APIs |  300 |         ✅          |               ✅               |
|    Error |                                                              Runtime errors |  400 |         ✅          |               ✅               |
| Critical |                                                         Critical conditions |  500 |         ✅          |               ✅               |


