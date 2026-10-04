<?php
namespace App\Enum;

enum UserRole: string
{
    case Executive = 'executive';
    case SectionLeader = 'section_leader';
    case Coordinator = 'coordinator';
    case Technical = 'technical';
}


?>