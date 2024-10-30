<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Attribute' => $vendorDir . '/symfony/polyfill-php80/Resources/stubs/Attribute.php',
    'Coderatio\\SimpleBackup\\Exceptions\\NoTablesFoundException' => $vendorDir . '/coderatio/simple-backup/src/Exceptions/NoTablesFoundException.php',
    'Coderatio\\SimpleBackup\\Foundation\\CompressBzip2' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Mysqldump.php',
    'Coderatio\\SimpleBackup\\Foundation\\CompressGzip' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Mysqldump.php',
    'Coderatio\\SimpleBackup\\Foundation\\CompressManagerFactory' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Mysqldump.php',
    'Coderatio\\SimpleBackup\\Foundation\\CompressMethod' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Mysqldump.php',
    'Coderatio\\SimpleBackup\\Foundation\\CompressNone' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Mysqldump.php',
    'Coderatio\\SimpleBackup\\Foundation\\Configurator' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Configurator.php',
    'Coderatio\\SimpleBackup\\Foundation\\Database' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Database.php',
    'Coderatio\\SimpleBackup\\Foundation\\Mysqldump' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Mysqldump.php',
    'Coderatio\\SimpleBackup\\Foundation\\Provider' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Provider.php',
    'Coderatio\\SimpleBackup\\Foundation\\TypeAdapter' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Mysqldump.php',
    'Coderatio\\SimpleBackup\\Foundation\\TypeAdapterDblib' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Mysqldump.php',
    'Coderatio\\SimpleBackup\\Foundation\\TypeAdapterFactory' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Mysqldump.php',
    'Coderatio\\SimpleBackup\\Foundation\\TypeAdapterMysql' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Mysqldump.php',
    'Coderatio\\SimpleBackup\\Foundation\\TypeAdapterPgsql' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Mysqldump.php',
    'Coderatio\\SimpleBackup\\Foundation\\TypeAdapterSqlite' => $vendorDir . '/coderatio/simple-backup/src/Foundation/Mysqldump.php',
    'Coderatio\\SimpleBackup\\SimpleBackup' => $vendorDir . '/coderatio/simple-backup/src/SimpleBackup.php',
    'Composer\\InstalledVersions' => $vendorDir . '/composer/InstalledVersions.php',
    'HuskyPress\\Admin\\AdminAjax' => $baseDir . '/includes/admin/class-admin-ajax.php',
    'HuskyPress\\Admin\\AdminAssets' => $baseDir . '/includes/admin/class-admin-assets.php',
    'HuskyPress\\Admin\\AdminHooks' => $baseDir . '/includes/admin/class-admin-hooks.php',
    'HuskyPress\\Admin\\AdminMenu' => $baseDir . '/includes/admin/class-admin-menu.php',
    'HuskyPress\\Backups' => $baseDir . '/includes/class-backups.php',
    'HuskyPress\\Backups\\BackupBuilder' => $baseDir . '/includes/backups/class-backup-builder.php',
    'HuskyPress\\Backups\\Destinations\\HuskyPressDestination' => $baseDir . '/includes/backups/destinations/class-husky-press-destination.php',
    'HuskyPress\\Backups\\Processors\\ZipProcessor' => $baseDir . '/includes/backups/processors/class-zip-processor.php',
    'HuskyPress\\Backups\\Types\\FilesFinderSource' => $baseDir . '/includes/backups/types/class-files-finder-source.php',
    'HuskyPress\\Backups\\Types\\MySQLDumpSource' => $baseDir . '/includes/backups/types/class-mysql-dump-source.php',
    'HuskyPress\\Backups\\Types\\MySQLManualSource' => $baseDir . '/includes/backups/types/class-mysql-manual-source.php',
    'HuskyPress\\Errors' => $baseDir . '/includes/class-errors.php',
    'HuskyPress\\Helpers\\BackupProfile' => $baseDir . '/includes/helpers/class-backup-profile.php',
    'HuskyPress\\Helpers\\Interfaces\\BackupBuilderInterface' => $baseDir . '/includes/helpers/interfaces/interface-backup-builder.php',
    'HuskyPress\\Helpers\\Interfaces\\BackupDestination' => $baseDir . '/includes/helpers/interfaces/interface-backup-destination.php',
    'HuskyPress\\Helpers\\Interfaces\\BackupProcessor' => $baseDir . '/includes/helpers/interfaces/interface-backup-processor.php',
    'HuskyPress\\Helpers\\Interfaces\\BackupProfileInterface' => $baseDir . '/includes/helpers/interfaces/interface-backup-profile.php',
    'HuskyPress\\Helpers\\Interfaces\\BackupSource' => $baseDir . '/includes/helpers/interfaces/interface-backup-source.php',
    'HuskyPress\\Installer' => $baseDir . '/includes/class-installer.php',
    'HuskyPress\\Rest\\Admin' => $baseDir . '/includes/rest/class-admin.php',
    'HuskyPress\\Rest\\Backups' => $baseDir . '/includes/rest/class-backups.php',
    'HuskyPress\\Rest\\Helpers' => $baseDir . '/includes/rest/class-helpers.php',
    'HuskyPress\\Rest\\Issues' => $baseDir . '/includes/rest/class-issues.php',
    'HuskyPress\\Rest\\Plugin' => $baseDir . '/includes/rest/class-plugin.php',
    'HuskyPress\\Rest\\Shared' => $baseDir . '/includes/rest/class-shared.php',
    'HuskyPress\\Rest\\Themes' => $baseDir . '/includes/rest/class-themes.php',
    'HuskyPress\\Settings' => $baseDir . '/includes/class-settings.php',
    'HuskyPress\\Shared\\Backups' => $baseDir . '/includes/shared/class-backups.php',
    'HuskyPress\\Shared\\SafeUpdate' => $baseDir . '/includes/shared/class-safe-update.php',
    'HuskyPress\\Shared\\SharedHooks' => $baseDir . '/includes/shared/class-shared-hooks.php',
    'HuskyPress\\Shared\\Snapshot' => $baseDir . '/includes/shared/class-snapshot.php',
    'HuskyPress\\Shared\\TrackErrors' => $baseDir . '/includes/shared/class-track-errors.php',
    'HuskyPress\\Skins\\PluginRollbacker' => $baseDir . '/includes/skins/class-plugin-rollbacker.php',
    'HuskyPress\\Skins\\RollbackSkin' => $baseDir . '/includes/skins/class-rollback-skin.php',
    'HuskyPress\\Traits\\Ajax' => $baseDir . '/includes/traits/trait-ajax.php',
    'MyThemeShop\\Admin\\List_Table' => $vendorDir . '/mythemeshop/wordpress-helpers/src/admin/class-list-table.php',
    'MyThemeShop\\Admin\\Page' => $vendorDir . '/mythemeshop/wordpress-helpers/src/admin/class-page.php',
    'MyThemeShop\\Database\\Clauses' => $vendorDir . '/mythemeshop/wordpress-helpers/src/database/class-clauses.php',
    'MyThemeShop\\Database\\Database' => $vendorDir . '/mythemeshop/wordpress-helpers/src/database/class-database.php',
    'MyThemeShop\\Database\\Escape' => $vendorDir . '/mythemeshop/wordpress-helpers/src/database/class-escape.php',
    'MyThemeShop\\Database\\GroupBy' => $vendorDir . '/mythemeshop/wordpress-helpers/src/database/class-groupby.php',
    'MyThemeShop\\Database\\Joins' => $vendorDir . '/mythemeshop/wordpress-helpers/src/database/class-joins.php',
    'MyThemeShop\\Database\\OrderBy' => $vendorDir . '/mythemeshop/wordpress-helpers/src/database/class-orderby.php',
    'MyThemeShop\\Database\\Query_Builder' => $vendorDir . '/mythemeshop/wordpress-helpers/src/database/class-query-builder.php',
    'MyThemeShop\\Database\\Select' => $vendorDir . '/mythemeshop/wordpress-helpers/src/database/class-select.php',
    'MyThemeShop\\Database\\Translate' => $vendorDir . '/mythemeshop/wordpress-helpers/src/database/class-translate.php',
    'MyThemeShop\\Database\\Where' => $vendorDir . '/mythemeshop/wordpress-helpers/src/database/class-where.php',
    'MyThemeShop\\Helpers\\Arr' => $vendorDir . '/mythemeshop/wordpress-helpers/src/helpers/class-arr.php',
    'MyThemeShop\\Helpers\\Attachment' => $vendorDir . '/mythemeshop/wordpress-helpers/src/helpers/class-attachment.php',
    'MyThemeShop\\Helpers\\Conditional' => $vendorDir . '/mythemeshop/wordpress-helpers/src/helpers/class-conditional.php',
    'MyThemeShop\\Helpers\\DB' => $vendorDir . '/mythemeshop/wordpress-helpers/src/helpers/class-db.php',
    'MyThemeShop\\Helpers\\HTML' => $vendorDir . '/mythemeshop/wordpress-helpers/src/helpers/class-html.php',
    'MyThemeShop\\Helpers\\Param' => $vendorDir . '/mythemeshop/wordpress-helpers/src/helpers/class-param.php',
    'MyThemeShop\\Helpers\\Str' => $vendorDir . '/mythemeshop/wordpress-helpers/src/helpers/class-str.php',
    'MyThemeShop\\Helpers\\Url' => $vendorDir . '/mythemeshop/wordpress-helpers/src/helpers/class-url.php',
    'MyThemeShop\\Helpers\\WordPress' => $vendorDir . '/mythemeshop/wordpress-helpers/src/helpers/class-wordpress.php',
    'MyThemeShop\\Json_Manager' => $vendorDir . '/mythemeshop/wordpress-helpers/src/class-json-manager.php',
    'MyThemeShop\\Notification' => $vendorDir . '/mythemeshop/wordpress-helpers/src/class-notification.php',
    'MyThemeShop\\Notification_Center' => $vendorDir . '/mythemeshop/wordpress-helpers/src/class-notification-center.php',
    'PhpToken' => $vendorDir . '/symfony/polyfill-php80/Resources/stubs/PhpToken.php',
    'Stringable' => $vendorDir . '/symfony/polyfill-php80/Resources/stubs/Stringable.php',
    'Symfony\\Component\\Finder\\Comparator\\Comparator' => $vendorDir . '/symfony/finder/Comparator/Comparator.php',
    'Symfony\\Component\\Finder\\Comparator\\DateComparator' => $vendorDir . '/symfony/finder/Comparator/DateComparator.php',
    'Symfony\\Component\\Finder\\Comparator\\NumberComparator' => $vendorDir . '/symfony/finder/Comparator/NumberComparator.php',
    'Symfony\\Component\\Finder\\Exception\\AccessDeniedException' => $vendorDir . '/symfony/finder/Exception/AccessDeniedException.php',
    'Symfony\\Component\\Finder\\Exception\\DirectoryNotFoundException' => $vendorDir . '/symfony/finder/Exception/DirectoryNotFoundException.php',
    'Symfony\\Component\\Finder\\Finder' => $vendorDir . '/symfony/finder/Finder.php',
    'Symfony\\Component\\Finder\\Gitignore' => $vendorDir . '/symfony/finder/Gitignore.php',
    'Symfony\\Component\\Finder\\Glob' => $vendorDir . '/symfony/finder/Glob.php',
    'Symfony\\Component\\Finder\\Iterator\\CustomFilterIterator' => $vendorDir . '/symfony/finder/Iterator/CustomFilterIterator.php',
    'Symfony\\Component\\Finder\\Iterator\\DateRangeFilterIterator' => $vendorDir . '/symfony/finder/Iterator/DateRangeFilterIterator.php',
    'Symfony\\Component\\Finder\\Iterator\\DepthRangeFilterIterator' => $vendorDir . '/symfony/finder/Iterator/DepthRangeFilterIterator.php',
    'Symfony\\Component\\Finder\\Iterator\\ExcludeDirectoryFilterIterator' => $vendorDir . '/symfony/finder/Iterator/ExcludeDirectoryFilterIterator.php',
    'Symfony\\Component\\Finder\\Iterator\\FileTypeFilterIterator' => $vendorDir . '/symfony/finder/Iterator/FileTypeFilterIterator.php',
    'Symfony\\Component\\Finder\\Iterator\\FilecontentFilterIterator' => $vendorDir . '/symfony/finder/Iterator/FilecontentFilterIterator.php',
    'Symfony\\Component\\Finder\\Iterator\\FilenameFilterIterator' => $vendorDir . '/symfony/finder/Iterator/FilenameFilterIterator.php',
    'Symfony\\Component\\Finder\\Iterator\\LazyIterator' => $vendorDir . '/symfony/finder/Iterator/LazyIterator.php',
    'Symfony\\Component\\Finder\\Iterator\\MultiplePcreFilterIterator' => $vendorDir . '/symfony/finder/Iterator/MultiplePcreFilterIterator.php',
    'Symfony\\Component\\Finder\\Iterator\\PathFilterIterator' => $vendorDir . '/symfony/finder/Iterator/PathFilterIterator.php',
    'Symfony\\Component\\Finder\\Iterator\\RecursiveDirectoryIterator' => $vendorDir . '/symfony/finder/Iterator/RecursiveDirectoryIterator.php',
    'Symfony\\Component\\Finder\\Iterator\\SizeRangeFilterIterator' => $vendorDir . '/symfony/finder/Iterator/SizeRangeFilterIterator.php',
    'Symfony\\Component\\Finder\\Iterator\\SortableIterator' => $vendorDir . '/symfony/finder/Iterator/SortableIterator.php',
    'Symfony\\Component\\Finder\\Iterator\\VcsIgnoredFilterIterator' => $vendorDir . '/symfony/finder/Iterator/VcsIgnoredFilterIterator.php',
    'Symfony\\Component\\Finder\\SplFileInfo' => $vendorDir . '/symfony/finder/SplFileInfo.php',
    'Symfony\\Component\\Process\\Exception\\ExceptionInterface' => $vendorDir . '/symfony/process/Exception/ExceptionInterface.php',
    'Symfony\\Component\\Process\\Exception\\InvalidArgumentException' => $vendorDir . '/symfony/process/Exception/InvalidArgumentException.php',
    'Symfony\\Component\\Process\\Exception\\LogicException' => $vendorDir . '/symfony/process/Exception/LogicException.php',
    'Symfony\\Component\\Process\\Exception\\ProcessFailedException' => $vendorDir . '/symfony/process/Exception/ProcessFailedException.php',
    'Symfony\\Component\\Process\\Exception\\ProcessSignaledException' => $vendorDir . '/symfony/process/Exception/ProcessSignaledException.php',
    'Symfony\\Component\\Process\\Exception\\ProcessTimedOutException' => $vendorDir . '/symfony/process/Exception/ProcessTimedOutException.php',
    'Symfony\\Component\\Process\\Exception\\RuntimeException' => $vendorDir . '/symfony/process/Exception/RuntimeException.php',
    'Symfony\\Component\\Process\\ExecutableFinder' => $vendorDir . '/symfony/process/ExecutableFinder.php',
    'Symfony\\Component\\Process\\InputStream' => $vendorDir . '/symfony/process/InputStream.php',
    'Symfony\\Component\\Process\\PhpExecutableFinder' => $vendorDir . '/symfony/process/PhpExecutableFinder.php',
    'Symfony\\Component\\Process\\PhpProcess' => $vendorDir . '/symfony/process/PhpProcess.php',
    'Symfony\\Component\\Process\\Pipes\\AbstractPipes' => $vendorDir . '/symfony/process/Pipes/AbstractPipes.php',
    'Symfony\\Component\\Process\\Pipes\\PipesInterface' => $vendorDir . '/symfony/process/Pipes/PipesInterface.php',
    'Symfony\\Component\\Process\\Pipes\\UnixPipes' => $vendorDir . '/symfony/process/Pipes/UnixPipes.php',
    'Symfony\\Component\\Process\\Pipes\\WindowsPipes' => $vendorDir . '/symfony/process/Pipes/WindowsPipes.php',
    'Symfony\\Component\\Process\\Process' => $vendorDir . '/symfony/process/Process.php',
    'Symfony\\Component\\Process\\ProcessUtils' => $vendorDir . '/symfony/process/ProcessUtils.php',
    'Symfony\\Polyfill\\Php80\\Php80' => $vendorDir . '/symfony/polyfill-php80/Php80.php',
    'Symfony\\Polyfill\\Php80\\PhpToken' => $vendorDir . '/symfony/polyfill-php80/PhpToken.php',
    'UnhandledMatchError' => $vendorDir . '/symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
    'ValueError' => $vendorDir . '/symfony/polyfill-php80/Resources/stubs/ValueError.php',
    'WP_Async_Request' => $vendorDir . '/a5hleyrich/wp-background-processing/classes/wp-async-request.php',
    'WP_Background_Process' => $vendorDir . '/a5hleyrich/wp-background-processing/classes/wp-background-process.php',
);
