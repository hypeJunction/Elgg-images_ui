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
  await page.fill('input[name="username"]', username);
  await page.fill('input[name="password"]', password);
  await page.click('button[type="submit"], input[type="submit"]');
  await page.waitForURL(/\//);
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
  const rows = await queryDb(
    `SELECT e.guid FROM elgg_entities e
     JOIN elgg_users_entity u ON u.guid = e.guid
     WHERE u.username = ?`,
    [username]
  );
  return rows[0]?.guid ?? null;
}

export async function deleteEntity(guid: number): Promise<void> {
  await queryDb('DELETE FROM elgg_entities WHERE guid = ?', [guid]);
}
