<?php

namespace Infrastructure\Game;

class ELOPlayer {
    public int $id = -1;
    public int $place = 0;
    public int $eloPre = 0;
    public int $eloPost = 0;
    public int $eloChange = 0;
}