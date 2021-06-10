<?php

namespace ukickeru\AccessControl\Model\Routes;

class ApplicationRoutesContainer
{

    public const GUARANTEED_ACCESSIBLE_ROUTES = [
        self::INDEX_NAME,
        self::LOGIN_ROUTE_NAME,
        self::LOGOUT_ROUTE_NAME,
        self::ACCOUNT_ROUTE_NAME,
        self::ACCOUNT_GROUPS_ROUTE_NAME,
        self::ACCOUNT_SETTINGS_ROUTE_NAME
    ];

    public const GUARANTEED_ACCESSIBLE_ROUTES_FOR_ADMIN = [
        self::USER_INDEX_NAME,
        self::USER_NEW_NAME,
        self::USER_SHOW_NAME,
        self::USER_EDIT_NAME,
        self::USER_DELETE_NAME,
        self::GROUP_INDEX_NAME,
        self::GROUP_NEW_NAME,
        self::GROUP_SHOW_NAME,
        self::GROUP_EDIT_NAME,
        self::GROUP_DELETE_NAME,
        self::CHANGE_ADMIN_NAME
    ];

    public const INDEX_NAME = 'app_index';
    public const INDEX_PATH = '/';

    public const LOGIN_ROUTE_NAME = 'app_login';
    public const LOGIN_ROUTE_PATH = '/login';

    public const LOGOUT_ROUTE_NAME = 'app_logout';
    public const LOGOUT_ROUTE_PATH = '/logout';

    public const ACCOUNT_ROUTE_NAME = 'account_index';
    public const ACCOUNT_ROUTE_PATH = '/account_index';

    public const ACCOUNT_GROUPS_ROUTE_NAME = 'account_groups';
    public const ACCOUNT_GROUPS_ROUTE_PATH = '/account_groups';

    public const ACCOUNT_SETTINGS_ROUTE_NAME = 'account_settings';
    public const ACCOUNT_SETTINGS_ROUTE_PATH = '/account_settings';

    /** @todo Дописать константами пути и использовтаь в контроллерах */
    public const USER_INDEX_NAME = 'user_index';
    public const USER_INDEX_PATH = self::INDEX_PATH.'users/';

    public const USER_NEW_NAME = 'user_new';
    public const USER_NEW_PATH = self::USER_INDEX_PATH.'new';

    public const USER_SHOW_NAME = 'user_show';
    public const USER_SHOW_PATH = self::USER_INDEX_PATH.'{id}';

    public const USER_EDIT_NAME = 'user_edit';
    public const USER_EDIT_PATH = self::USER_INDEX_PATH.'{id}/edit';

    public const USER_DELETE_NAME = 'user_delete';
    public const USER_DELETE_PATH = self::USER_INDEX_PATH.'{id}';

    public const GROUP_INDEX_NAME = 'group_index';
    public const GROUP_INDEX_PATH = self::INDEX_PATH.'groups/';

    public const GROUP_NEW_NAME = 'group_new';
    public const GROUP_NEW_PATH = self::GROUP_INDEX_PATH.'new';

    public const GROUP_SHOW_NAME = 'group_show';
    public const GROUP_SHOW_PATH = self::GROUP_INDEX_PATH.'{id}';

    public const GROUP_EDIT_NAME = 'group_edit';
    public const GROUP_EDIT_PATH = self::GROUP_INDEX_PATH.'{id}/edit';

    public const GROUP_DELETE_NAME = 'group_delete';
    public const GROUP_DELETE_PATH = self::GROUP_INDEX_PATH.'{id}';

    public const CHANGE_ADMIN_NAME = 'change_admin';
    public const CHANGE_ADMIN_PATH = self::INDEX_PATH.'change_admin';

    protected $routesCollection;

    public function __construct(RoutesGetterInterface $routesCreator)
    {
        $this->routesCollection = $routesCreator->createRoutesCollection();
    }

    /**
     * @return iterable|Route[]
     */
    public function getRoutes(): iterable
    {
        return $this->routesCollection;
    }
}
