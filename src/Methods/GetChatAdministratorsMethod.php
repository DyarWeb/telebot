<?php

namespace WeStacks\TeleBot\Methods;

use WeStacks\TeleBot\Contracts\TelegramMethod;

/**
 * Use this method to get a list of administrators in a chat. On success, returns an Array of [ChatMember](https://core.telegram.org/bots/api#chatmember) objects that contains information about all chat administrators except other bots. If the chat is a group or a supergroup and no administrators were appointed, only the creator will be returned.
 *
 * @property string $chat_id __Required: Yes__. Unique identifier for the target chat or username of the target supergroup or channel (in the format @channelusername)
 */
class GetChatAdministratorsMethod extends TelegramMethod
{
    protected string $method = 'getChatAdministrators';

    protected string $expect = 'ChatMember[]';

    protected array $parameters = [
        'chat_id' => 'string',
    ];
}
