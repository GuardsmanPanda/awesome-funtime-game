<?php

namespace Infrastructure\Game;

class ELOPlayer {
    public int $id = -1;
    public $place = 0;
    public $eloPre = 0;
    public $eloPost = 0;
    public $eloChange = 0;
}