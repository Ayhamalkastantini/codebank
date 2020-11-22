<?php

use App\BaseRepository;

/**
 * Create DB tables, indexes & relations
 *
 * @return void
 */
function createTables()
{
    /**
     * Tables' structure
     */
    $tablesStructures = [
        "CREATE TABLE IF NOT EXISTS `users` (
            `id` INT UNSIGNED NOT NULL,
            `roleId` INT NOT NULL,
            `email` TINYTEXT NOT NULL,
            `password` TINYTEXT NOT NULL,
            `secret` TINYTEXT NOT NULL,
            `tagLine` TINYTEXT NOT NULL,
            `createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updatedAt` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

        "CREATE TABLE IF NOT EXISTS `code` (
            `id` INT UNSIGNED NOT NULL,
            `category` VARCHAR(50) NOT NULL,
            `slug` VARCHAR(255) NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `subtitle` TINYTEXT NOT NULL,
            `body` LONGTEXT NOT NULL,
            `createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updatedAt` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

        "CREATE TABLE IF NOT EXISTS `code_users` (
            `id` INT UNSIGNED NOT NULL,
            `codeId` INT NOT NULL,
            `userId` INT NOT NULL,
            `kind` INT NOT NULL,
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

        "CREATE TABLE IF NOT EXISTS `role` (
            `id` INT UNSIGNED NOT NULL,
            `role` VARCHAR(50) NOT NULL,
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
    ];

    /**
     * Indexes
     */
    $tablesIndexes = [
        "ALTER TABLE `users` ADD PRIMARY KEY (`id`);",
        "ALTER TABLE `code` ADD PRIMARY KEY (`id`);",
        "ALTER TABLE `code_users` ADD PRIMARY KEY (`id`);",
        "ALTER TABLE `role` ADD PRIMARY KEY (`id`);"
    ];

    /**
     * Auto increments
     */
    $tablesAutoIncrements = [
        "ALTER TABLE `users` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `code` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `code_users` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT;",
        "ALTER TABLE `role` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT;"
    ];

    /**
     * Foreign keys
     */
    $tablesForeignKeys = [
        "ALTER TABLE `code_users` ADD FOREIGN KEY (`codeId`) REFERENCES `code`(`id`);",
        "ALTER TABLE `code_users` ADD FOREIGN KEY (`userId`) REFERENCES `users`(`id`);",
        "ALTER TABLE `users` ADD FOREIGN KEY (`roleId`) REFERENCES `role`(`id`);"
    ];

    $baseRepository = new BaseRepository();
    foreach ($tablesStructures as $tablesStructure) {
        $baseRepository->query($tablesStructure);
        $baseRepository->execute();
    }
    foreach ($tablesIndexes as $tablesIndex) {
        $baseRepository->query($tablesIndex);
        $baseRepository->execute();
    }
    foreach ($tablesAutoIncrements as $tablesAutoIncrement) {
        $baseRepository->query($tablesAutoIncrement);
        $baseRepository->execute();
    }
    foreach ($tablesForeignKeys as $tablesForeignKey) {
        $baseRepository->query($tablesForeignKey);
        $baseRepository->execute();
    }

    /**
     * Prevent to create existed tables by commenting a command that call this function
     */
    $path_to_file = dirname(__DIR__) . '/src/routes.php';
    $file_contents = file_get_contents($path_to_file);
    $file_contents = str_replace("createTables();", "// createTables();", $file_contents);
    file_put_contents($path_to_file, $file_contents);
}
