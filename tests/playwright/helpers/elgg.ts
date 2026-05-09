import { Page } from '@playwright/test';
import mysql from 'mysql2/promise';

const DB_CONFIG = {
  host: process.env.ELGG_DB_HOST || 'db',
  port: Number(process.env.ELGG_DB_PORT || 3306),
  user: process.env.ELGG_DB_USER || 'elgg',
  password: process.env.ELGG_DB_PASS || 'elgg',
  database: process.env.ELGG_DB_NAME || 'elgg',
};

export async function loginAs(page: Page, username: string, password: string = 'testpass123') {
  await page.goto('/login');
  // Elgg renders two login forms: a hidden header dropdown and a visible sidebar form.
  // The sidebar form (.elgg-module-aside) must be targeted to avoid filling the hidden one.
  await page.fill('.elgg-module-aside input[name="username"]', username);
  await page.fill('.elgg-module-aside input[name="password"]', password);
  await page.click('.elgg-module-aside button[type="submit"], .elgg-module-aside input[type="submit"]');
  // Wait until navigation leaves the login page (the /\/\// regex matches immediately).
  await page.waitForURL((url) => !url.pathname.startsWith('/login'), { timeout: 10000 });
}

export async function queryDb(sql: string, params: any[] = []): Promise<any[]> {
  const conn = await mysql.createConnection(DB_CONFIG);
  const [rows] = await conn.execute(sql, params);
  await conn.end();
  return rows as any[];
}

export async function getImageFilesByOwner(ownerGuid: number): Promise<any[]> {
  return queryDb(
    `SELECT e.* FROM elgg_entities e
     JOIN elgg_metadata m ON m.entity_guid = e.guid AND m.name = 'simpletype'
     WHERE e.type = 'object' AND e.subtype = 'file'
       AND m.value = 'image' AND e.owner_guid = ?
     ORDER BY e.guid DESC`,
    [ownerGuid]
  );
}

export async function getMetadata(entityGuid: number, name: string): Promise<any[]> {
  return queryDb(
    'SELECT * FROM elgg_metadata WHERE entity_guid = ? AND name = ?',
    [entityGuid, name]
  );
}

export async function getUserGuidByUsername(username: string): Promise<number | null> {
  // elgg_users_entity was removed in Elgg 5.x; username lives in elgg_metadata
  const rows = await queryDb(
    `SELECT e.guid FROM elgg_entities e
     JOIN elgg_metadata m ON m.entity_guid = e.guid
     WHERE e.type = 'user' AND m.name = 'username' AND m.value = ?`,
    [username]
  );
  return rows[0]?.guid ?? null;
}

export async function deleteEntity(guid: number): Promise<void> {
  await queryDb('DELETE FROM elgg_entities WHERE guid = ?', [guid]);
}
