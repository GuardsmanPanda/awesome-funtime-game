<?php

namespace Areas\Stat;

use App\Tools\Resp;
use Illuminate\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatController extends Controller {
    public function index(): view  {
        return view('stat.index', [
            'info' => DB::selectOne("
                SELECT
                    (SELECT COUNT(*) FROM users) as user_count,
                    (SELECT COUNT(*) FROM game) as game_count,
                    (SELECT COUNT(*) FROM round) as round_count,
                    (SELECT COUNT(*) FROM round_user) as guess_count,
                    (SELECT COUNT(*) FROM panorama) as panorama_count
            "),
        ]);
    }

    public function getUserPanoramaStats(): JsonResponse {
        return Resp::SQLJson("
            SELECT
                COALESCE(u.display_name, 'System') AS display_name,
                COALESCE(u.country_code, 'XX') AS country_code,
                COALESCE(c.country_name, 'Unknown') AS country_name,
                COUNT(*) as count,
                SUM(data.wow) as wow,
                ROUND(SUM(data.wow) / (SUM(data.total)+1)*100) as wow_percent,
                SUM(data.great) as great,
                ROUND(SUM(data.great) / (SUM(data.total)+1)*100) as great_percent,
                SUM(data.good) as good,
                ROUND(SUM(data.good) / (SUM(data.total)+1)*100) as good_percent,
                SUM(data.total) as total,
                ROUND((SUM(data.good)+SUM(data.great)+SUM(data.wow)) / (SUM(data.total)+1)*100) as good_or_better_percent
            FROM panorama p2
            LEFT JOIN (
                SELECT
                    p.added_by_user_id, p.panorama_id,
                    COALESCE(SUM(1) FILTER ( WHERE pr.rating = 7 ), 0) as wow,
                    COALESCE(SUM(1) FILTER ( WHERE pr.rating = 6 ), 0) as great,
                    COALESCE(SUM(1) FILTER ( WHERE pr.rating = 5 ), 0) as good,
                    COALESCE(SUM(1) FILTER ( WHERE pr.rating IS NOT NULL ), 0) AS total
                FROM panorama p
                LEFT JOIN panorama_rating pr on p.panorama_id = pr.panorama_id
                GROUP BY p.panorama_id
                 ) AS data ON p2.panorama_id = data.panorama_id
            LEFT JOIN users u ON u.id = p2.added_by_user_id
            LEFT JOIN country c ON c.country_code = u.country_code
            GROUP BY u.id, c.country_code
            ORDER BY count DESC
        ");
    }
}