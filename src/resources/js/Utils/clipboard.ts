type CopyOptions = {
  onSuccess?: () => void;
  onError?: (error: unknown) => void;
};

export async function copyToClipboard(text: string, options?: CopyOptions): Promise<void> {
  try {
    await navigator.clipboard.writeText(text);

    options?.onSuccess?.();
  } catch (error) {
    options?.onError?.(error);
  }
}
