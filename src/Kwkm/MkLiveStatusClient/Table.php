<?php
namespace Kwkm\MkLiveStatusClient;

/**
 * Class Table
 *
 * @package Kwkm\MkLiveStatusClient
 * @author Takehiro Kawakami <take@kwkm.org>
 * @license MIT
 */
class Table
{
    /**
     * Nagios hosts.
     */
    const HOSTS = 'hosts';

    /**
     * Nagios services, joined with all data from hosts.
     */
    const SERVICES = 'services';

    /**
     * Nagios hostgroups.
     */
    const HOST_GROUPS = 'hostgroups';

    /**
     * Nagios servicegroups.
     */
    const SERVICE_GROUPS = 'servicegroups';

    /**
     * Nagios contact groups.
     */
    const CONTACT_GROUPS = 'contactgroups';

    /**
     * all services grouped by service groups.
     */
    const SERVICES_BY_GROUP = 'servicesbygroup';

    /**
     * all services grouped by host groups.
     */
    const SERVICES_BY_HOST_GROUP = 'servicesbyhostgroup';

    /**
     * all hosts group by host groups.
     */
    const HOSTS_BY_GROUP = 'hostsbygroup';

    /**
     * Nagios contacts.
     */
    const CONTACTS = 'contacts';

    /**
     * Nagios commands.
     */
    const COMMANDS = 'commands';

    /**
     * time period definitions.
     */
    const TIME_PERIODS = 'timeperiods';

    /**
     * all scheduled host and service downtimes, joined with data from hosts and services.
     */
    const DOWNTIMES = 'downtimes';

    /**
     * all host and service comments.
     */
    const COMMENTS = 'comments';

    /**
     * a transparent access to the nagios logfiles.
     */
    const LOG = 'log';

    /**
     * general performance and status information.
     */
    const STATUS = 'status';

    /**
     * a complete list of all tables and columns available via Livestatus.
     */
    const COLUMNS = 'columns';

    /**
     * sla statistics for hosts and services, joined with data from hosts, services and log.
     */
    const SLA_STATISTICS = 'statehist';
}
