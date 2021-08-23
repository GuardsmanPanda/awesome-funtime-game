<?php

namespace Infrastructure\Game;

class CalculateElo {
    private array $players = array();

    public function addPlayer(int $id, int $place, int $elo): void {
        $player = new ELOPlayer();
        $player->id = $id;
        $player->place = $place;
        $player->eloPre = $elo;
        $this->players[] = $player;
    }

    public function getELO(int $id): int {
        foreach ($this->players as $p) {
            if ($p->id === $id) {
                return $p->eloPost;
            }
        }
        return 1600;
    }

    public function getELOChange(int $id): int {
        foreach ($this->players as $p) {
            if ($p->id === $id) {
                return $p->eloChange;
            }
        }
        return 0;
    }

    public function calculateELOs(): void {
        $n = count($this->players);
        $K = 42.0 / ($n - 1);

        foreach ($this->players as $i => $iValue) {
            $curPlace = $iValue->place;
            $curELO = $iValue->eloPre;
            $change = 0.0;
            foreach ($this->players as $j => $jValue) {
                if ($i !== $j) {
                    $opponentPlace = $jValue->place;
                    $opponentELO = $jValue->eloPre;

                    //work out S
                    if ($curPlace < $opponentPlace) {
                        $S = 1;
                    }
                    else if ($curPlace === $opponentPlace) {
                        $S = 0.5;
                    }
                    else {
                        $S = 0;
                    }

                    //work out EA
                    $EA = 1 / (1 + (10 ** (($opponentELO - $curELO) / 400)));

                    //calculate ELO change vs this one opponent, add it to our change bucket
                    //I currently round at this point, this keeps rounding changes symetrical between EA and EB, but changes K more than it should
                    $change += $K * ($S - $EA);
                }
            }
            //add accumulated change to initial ELO for final ELO
            $iValue->eloChange = (int)round($change);
            $iValue->eloPost = $iValue->eloPre + $iValue->eloChange;
        }
    }
}