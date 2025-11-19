const { Actor, HttpAgent } = require("@dfinity/agent");

// Imports and re-exports candid interface
const { idlFactory: ledgerIdlFactory } = require("./Token_ledger.did.js");
const { idlFactory: archiveIdlFactory } = require("./Token_archive.did.js");

exports.ledgerIdlFactory = ledgerIdlFactory;
exports.archiveIdlFactory = archiveIdlFactory;

/* CANISTER_ID is replaced by webpack based on node environment
 * Note: canister environment variable will be standardized as
 * process.env.CANISTER_ID_<CANISTER_NAME_UPPERCASE>
 * beginning in dfx 0.15.0
 */
const canisterId = "sddoy-iyaaa-aaaam-aclfq-cai";

exports.createActor = (canisterId, options = {}) => {
  const agent = options.agent || new HttpAgent({ ...options.agentOptions });

  if (options.agent && options.agentOptions) {
    console.warn(
      "Detected both agent and agentOptions passed to createActor. Ignoring agentOptions and proceeding with the provided agent."
    );
  }

  // Fetch root key for certificate validation during development
  if (process.env.DFX_NETWORK !== "ic") {
    agent.fetchRootKey().catch((err) => {
      console.warn(
        "Unable to fetch root key. Check to ensure that your local replica is running"
      );
      console.error(err);
    });
  }

  // Creates an actor with using the candid interface and the HttpAgent
  return Actor.createActor(ledgerIdlFactory, {
    agent,
    canisterId,
    ...options.actorOptions,
  });
};

exports.createArchiveActor = (canisterId, options = {}) => {
  const agent = options.agent || new HttpAgent({ ...options.agentOptions });

  if (options.agent && options.agentOptions) {
    console.warn(
      "Detected both agent and agentOptions passed to createActor. Ignoring agentOptions and proceeding with the provided agent."
    );
  }

  // Fetch root key for certificate validation during development
  if (process.env.DFX_NETWORK !== "ic") {
    agent.fetchRootKey().catch((err) => {
      console.warn(
        "Unable to fetch root key. Check to ensure that your local replica is running"
      );
      console.error(err);
    });
  }

  // Creates an actor with using the candid interface and the HttpAgent
  return Actor.createActor(archiveIdlFactory, {
    agent,
    canisterId,
    ...options.actorOptions,
  });
};

exports.BAT_ledger = canisterId ? exports.createActor(canisterId) : undefined;
