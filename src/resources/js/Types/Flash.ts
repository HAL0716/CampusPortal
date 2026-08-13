export type FlashMessage = {
  id: string;
  message: string;
};

export type FlashProps = {
  success?: FlashMessage;
  error?: FlashMessage;
};
