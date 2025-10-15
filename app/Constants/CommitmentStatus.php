<?php
namespace App\Constants;

enum CommitmentStatus: string
{
     case SCHEDULED = "scheduled";
     case CLOSED = "closed";
     case CANCELED = "canceled";
}
