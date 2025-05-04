<?php

namespace Msemeen\ErrorReporter\Enums;

enum ChannelType: string
{
    case DISCORD = 'discord';
    case EMAIL = 'email';
    case SLACK = 'slack';
}