<?php

namespace ukickeru\AccessControl\Model\Routes;

class ApplicationRoutesContainer
{

    public const GUARANTEED_ACCESSIBLE_ROUTES = [
        self::INDEX_PATH,
        self::LOGIN_ROUTE_PATH,
        self::LOGOUT_ROUTE_PATH,
        self::ACCOUNT_ROUTE_PATH,
        self::ACCOUNT_GROUPS_ROUTE_PATH,
        self::ACCOUNT_SETTINGS_ROUTE_PATH
    ];

    public const GUARANTEED_ACCESSIBLE_ROUTES_FOR_ADMIN = [
        self::USER_INDEX,
        self::USER_NEW,
        self::USER_SHOW,
        self::USER_EDIT,
        self::USER_DELETE,
        self::GROUP_INDEX,
        self::GROUP_NEW,
        self::GROUP_SHOW,
        self::GROUP_EDIT,
        self::GROUP_DELETE,
        self::CHANGE_ADMIN
    ];

    public const INDEX_PATH = 'app_index';

    public const LOGIN_ROUTE_PATH = 'login';

    public const LOGOUT_ROUTE_PATH = 'logout';

    public const ACCOUNT_ROUTE_PATH = 'account_index';

    public const ACCOUNT_GROUPS_ROUTE_PATH = 'account_groups';

    public const ACCOUNT_SETTINGS_ROUTE_PATH = 'account_settings';

    public const USER_INDEX = 'user_index';

    public const USER_NEW = 'user_new';

    public const USER_SHOW = 'user_show';

    public const USER_EDIT = 'user_edit';

    public const USER_DELETE = 'user_delete';

    public const GROUP_INDEX = 'group_index';

    public const GROUP_NEW = 'group_new';

    public const GROUP_SHOW = 'group_show';

    public const GROUP_EDIT = 'group_edit';

    public const GROUP_DELETE = 'group_delete';

    public const CHANGE_ADMIN = 'change_admin';

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
