<?php
namespace App\Services;

use App\Model\Message;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ChatService {

    public $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public function getMessages() {
        $messages = collect();
        $userLists = $this->getUserLists();
        foreach ($userLists as $user) {
            $userMessages = $this->getUserMessages($user->id);
            $messages[$user->id] = $userMessages;
        }
        return $messages;
    }

    /** 获取跟特定用户的聊天信息
     * @param $id 联系人id
     * @return \Illuminate\Support\Collection
     */
    public function getUserMessages($id) {
        $messages = collect();
        $messages = Message::where('user_id',$this->user->id)->where('target_id',$id)
            ->orWhere([['user_id','=',$id],['target_id','=',$this->user->id]])->orderBy('created_at','asc')->get();
        foreach ($messages as $message) {
            if ($message->user_id == $this->user->id) {
                $message->type = 'sent';
            } else {
                $message->type = 'receive';
            }

        }
        return $messages;
    }

    /** 获取最近联系人列表
     *
     * @return \Illuminate\Support\Collection
     */
    public function getUserLists() {
        $ids = collect();
        $userLists = collect();
        $to_ids = DB::table('messages')->where('user_id',$this->user->id)->pluck('target_id');
        $from_ids = DB::table('messages')->where('target_id',$this->user->id)->pluck('user_id');
        $ids = $ids->merge($to_ids)->merge($from_ids)->unique()->toArray();
        $userLists = User::whereIn('id',$ids)->get();
        foreach ($userLists as $user) {
            $count = $this->user->unReadMessagesFromUser($user->id)->count();
            $user->un_read_messages_count = $count;
        }
        return $userLists;
    }

    /** 把收到的未读信息标记未已读
     * @param $id 把该id的用户发送给$this->user的信息标记为已读
     * @return mixed
     */
    public function markAsRead($id) {
        $result = Message::where('status',0)->where('target_id',$this->user->id)->where('user_id',$id)->update(['status' => 1]);
        return $result;
    }

}