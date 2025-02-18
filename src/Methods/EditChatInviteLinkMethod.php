<?php

namespace WeStacks\TeleBot\Methods;

use WeStacks\TeleBot\Contracts\TelegramMethod;

/**
 * Use this method to edit a non-primary invite link created by the bot. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the edited invite link as a [ChatInviteLink](https://core.telegram.org/bots/api#chatinvitelink) object.
 *
 * @property string $chat_id              __Required: Yes__. Unique identifier for the target chat or username of the target channel (in the format @channelusername)
 * @property string $invite_link          __Required: Yes__. The invite link to edit
 * @property string $name                 __Required: Optional__. Invite link name; 0-32 characters
 * @property int    $expire_date          __Required: Optional__. Point in time (Unix timestamp) when the link will expire
 * @property int    $member_limit         __Required: Optional__. Maximum number of users that can be members of the chat simultaneously after joining the chat via this invite link; 1-99999
 * @property bool   $creates_join_request __Required: Optional__. True, if users joining the chat via the link need to be approved by chat administrators. If True, member_limit can't be specified
 */
class EditChatInviteLinkMethod extends TelegramMethod
{
    protected string $method = 'editChatInviteLink';

    protected string $expect = 'ChatInviteLink';

    protected array $parameters = [
        'chat_id' => 'string',
        'invite_link' => 'string',
        'name' => 'string',
        'expire_date' => 'integer',
        'member_limit' => 'integer',
        'creates_join_request' => 'boolean',
    ];
}
