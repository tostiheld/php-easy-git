<?php namespace Git;

class Repository
{
    private $repository;

    public function __construct($path)
    {
        $this->repository = git_repository_open($path);
    }

    public function __destruct()
    {
        git_repository_free($this->repository);
    }

    public static function init($path)
    {
        $repo = git_repository_init($path, false);
        git_repository_free($repo);

        return new Repository($path);
    }

    public function status($exclude_flags = GIT_STATUS_IGNORED | GIT_STATUS_CURRENT)
    {
        $list = git_status_list_new($this->repository, []);
        $list_count = git_status_list_entrycount($list);
        $output = [];

        for ($i = 0; $i < $list_count; $i++)
        {
            $item = git_status_byindex($list, $i);
            $status = $item['status'];

            if ($status & $exclude_flags)
            {
                continue;
            }
            else if (is_array($item['head_to_index']))
            {
                $output[
                    $item['head_to_index']['new_file']['path']
                ] = $status;
            }
            else if (is_array($item['index_to_workdir']))
            {
                $output[
                    $item['index_to_workdir']['new_file']['path']
                ] = $status;
            }
            else
            {
                continue;
            }
        }

        return $output;
    }

    public function staged()
    {
        return $this->statusList();

    }

    public function unstaged()
    {

    }

    public function untracked()
    {

    }
}