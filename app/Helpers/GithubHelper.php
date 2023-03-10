<?php
/**
 * Created by PhpStorm.
 * User: sumanas
 * Date: 13/4/18
 * Time: 1:03 PM
 */

namespace App\Helpers;


use App\Models\UserSettings;
use Carbon\Carbon;

class GithubHelper
{
    public $url = 'https://api.github.com';

    public $username = null;

    public $password = null;

    public function __construct()
    {
        $github_credentials = json_decode(UserSettings::fetch(UserSettings::GITHUB_CREDENTIALS), true);

        if($github_credentials){
            $this->username = $github_credentials['username'];
            $this->password = $github_credentials['personalaccesstoken'];
        }
    }

    public function getUserinfo()
    {
        return json_decode($this->callAPI('user'));
    }

    public function getUserRepos()
    {
        return json_decode($this->callAPI('user/repos'));
    }

    public function getUserLatestCommits()
    {
//        $search[] = 'committer-date:2018-10-25..2018-10-26';
        $search[] = 'committer-date:>'.Carbon::now()->subDay(3)->toDateString();
        $search[] = 'committer:'.$this->username;

        return json_decode($this->callAPI('search/commits?q='.implode('+', $search).'&sort=committer-date&order=desc'));
    }

    public function callAPI($path, $data = [], $method = 'GET')
    {
        $ch = curl_init();

        $header[] = 'User-Agent: '.$this->username;
        $header[] = 'Accept: application/vnd.github.cloak-preview';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $this->url.'/'.$path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}