<?php
namespace App\Constants;

enum CommitmentStatus: string
{
     case PENDING = "pending";
     case SCHEDULED = "scheduled";
     case RUNNING = "running";
     case CLOSED = "closed";
     case CANCELED = "canceled";
}
